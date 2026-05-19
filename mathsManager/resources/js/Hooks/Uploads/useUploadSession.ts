import { useCallback, useEffect, useRef, useState } from 'react';
import axios from 'axios';
import type { UploadedFileInfo, UploadPurpose, UploadSessionInfo } from '@/types/api';

interface UseUploadSessionOptions {
  purpose: UploadPurpose;
  pollInterval?: number;
}

interface UseUploadSessionResult {
  sessionToken: string | null;
  mobileUrl: string | null;
  files: UploadedFileInfo[];
  isReady: boolean;
  isUploading: boolean;
  error: string | null;
  uploadFile: (file: File) => Promise<void>;
  removeFile: (fileId: number) => Promise<void>;
  clearError: () => void;
}

export function useUploadSession({
  purpose,
  pollInterval = 3000,
}: UseUploadSessionOptions): UseUploadSessionResult {
  const [session, setSession] = useState<UploadSessionInfo | null>(null);
  const [files, setFiles] = useState<UploadedFileInfo[]>([]);
  const [isReady, setIsReady] = useState(false);
  const [isUploading, setIsUploading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const pollRef = useRef<ReturnType<typeof setInterval> | null>(null);

  useEffect(() => {
    axios
      .post<UploadSessionInfo>(route('uploads.sessions.create'), { purpose })
      .then((res) => {
        setSession(res.data);
        setIsReady(true);
      })
      .catch(() => setError("Impossible de créer la session d'upload."));
  }, [purpose]);

  useEffect(() => {
    if (!session) return;

    const poll = () => {
      axios
        .get<{ files: UploadedFileInfo[] }>(route('uploads.files.list', session.token))
        .then((res) => setFiles(res.data.files))
        .catch(() => {});
    };

    pollRef.current = setInterval(poll, pollInterval);
    return () => {
      if (pollRef.current) clearInterval(pollRef.current);
    };
  }, [session, pollInterval]);

  const uploadFile = useCallback(
    async (file: File) => {
      if (!session) return;

      setIsUploading(true);
      const formData = new FormData();
      formData.append('file', file);

      try {
        const res = await axios.post<UploadedFileInfo>(
          route('uploads.files.add', session.token),
          formData,
          { headers: { 'Content-Type': 'multipart/form-data' } }
        );
        setFiles((prev) => [...prev, res.data]);
      } catch {
        setError("Échec de l'upload. Vérifiez la taille (max 5 Mo).");
      } finally {
        setIsUploading(false);
      }
    },
    [session]
  );

  const removeFile = useCallback(
    async (fileId: number) => {
      if (!session) return;

      try {
        await axios.delete(route('uploads.files.delete', { token: session.token, upload: fileId }));
        setFiles((prev) => prev.filter((f) => f.id !== fileId));
      } catch {
        setError('Impossible de supprimer le fichier.');
      }
    },
    [session]
  );

  // Ziggy returns an absolute URL already; no need to prepend origin
  const mobileUrl = session ? route('uploads.mobile', session.token) : null;

  return {
    sessionToken: session?.token ?? null,
    mobileUrl,
    files,
    isReady,
    isUploading,
    error,
    uploadFile,
    removeFile,
    clearError: () => setError(null),
  };
}
