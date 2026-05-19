import { useState, useRef } from 'react';
import { router } from '@inertiajs/react';
import axios from 'axios';
import { PrivateExercise, TeacherTag, PrivateExerciseFormData, LatexField } from '@/types/models';

export type { PrivateExerciseFormData, LatexField };

interface PendingImage {
  file: File;
  blobUrl: string;
}

const EMPTY: PrivateExerciseFormData = {
  type: 'basic',
  name: '',
  notes: '',
  latex_statement: '',
  latex_solution: '',
  latex_clue: '',
  difficulty: '',
  time: '',
  classe_id: '',
  chapter_id: '',
  subchapter_id: '',
  tag_ids: [],
};

function fromExercise(e: PrivateExercise): PrivateExerciseFormData {
  return {
    type: e.type,
    name: e.name,
    notes: e.notes ?? '',
    latex_statement: e.latex_statement ?? '',
    latex_solution: e.latex_solution ?? '',
    latex_clue: e.latex_clue ?? '',
    difficulty: e.difficulty ? String(e.difficulty) : '',
    time: e.time ? String(e.time) : '',
    classe_id: e.classe_id ? String(e.classe_id) : '',
    chapter_id: e.chapter_id ? String(e.chapter_id) : '',
    subchapter_id: e.subchapter_id ? String(e.subchapter_id) : '',
    tag_ids: e.tags?.map((t) => t.id) ?? [],
  };
}

// ─── Hook ─────────────────────────────────────────────────────────────────────

export function usePrivateExerciseForm(exercise?: PrivateExercise | null) {
  const [data, setData] = useState<PrivateExerciseFormData>(
    exercise ? fromExercise(exercise) : EMPTY
  );
  const [processing, setProcessing] = useState(false);
  const [errors, setErrors] = useState<Partial<Record<keyof PrivateExerciseFormData, string>>>({});
  const [uploadingImage, setUploadingImage] = useState(false);
  const [imageError, setImageError] = useState<string | null>(null);

  // Images en attente (Create uniquement) : nom → { file, blobUrl }
  const [pendingImages, setPendingImages] = useState<Record<string, PendingImage>>({});
  const pendingCountRef = useRef(0);
  const lastFocusedFieldRef = useRef<LatexField>('latex_statement');

  function set<K extends keyof PrivateExerciseFormData>(key: K, value: PrivateExerciseFormData[K]) {
    setData((prev) => ({ ...prev, [key]: value }));
  }

  function setFocusedField(field: LatexField) {
    lastFocusedFieldRef.current = field;
  }

  /** Map nom → blobUrl pour LatexRenderer (Create) */
  const pendingImageMap: Record<string, string> = Object.fromEntries(
    Object.entries(pendingImages).map(([name, { blobUrl }]) => [name, blobUrl])
  );

  function addPendingImage(file: File) {
    pendingCountRef.current += 1;
    const name = `img-${pendingCountRef.current}`;
    const blobUrl = URL.createObjectURL(file);
    setPendingImages((prev) => ({ ...prev, [name]: { file, blobUrl } }));
  }

  function removePendingImage(name: string) {
    setPendingImages((prev) => {
      const next = { ...prev };
      URL.revokeObjectURL(next[name].blobUrl);
      delete next[name];
      return next;
    });
  }

  function buildPayload() {
    return {
      ...data,
      difficulty: data.difficulty ? Number(data.difficulty) : null,
      time: data.time ? Number(data.time) : null,
      classe_id: data.classe_id ? Number(data.classe_id) : null,
      chapter_id: data.chapter_id ? Number(data.chapter_id) : null,
      subchapter_id: data.subchapter_id ? Number(data.subchapter_id) : null,
    };
  }

  /**
   * Submit :
   * - Edit (routeParams fourni) → router.put Inertia classique
   * - Create (pas de routeParams) → router.post Inertia + upload d'images intégré
   */
  function submit(routeName: string, routeParams?: object, onSuccess?: () => void) {
    setProcessing(true);
    setErrors({});

    if (routeParams) {
      // ── Mode Edit ──
      const url = route(routeName, routeParams as Record<string, string | number>);
      router.put(url, buildPayload(), {
        preserveScroll: true,
        onSuccess: () => {
          setProcessing(false);
          onSuccess?.();
        },
        onError: (errs: Record<string, string>) => {
          setProcessing(false);
          setErrors(errs as Partial<Record<keyof PrivateExerciseFormData, string>>);
        },
      });
    } else {
      // ── Mode Create : submit Inertia avec images pending pour garder redirect + flash serveur ──
      const pendingFiles = Object.fromEntries(
        Object.entries(pendingImages).map(([name, pending]) => [name, pending.file])
      );

      router.post(
        route(routeName),
        {
          ...buildPayload(),
          pending_images: pendingFiles,
        },
        {
          forceFormData: true,
          preserveScroll: true,
          onSuccess: () => {
            Object.values(pendingImages).forEach(({ blobUrl }) => URL.revokeObjectURL(blobUrl));
            setPendingImages({});
            setProcessing(false);
            onSuccess?.();
          },
          onError: (errs: Record<string, string>) => {
            setProcessing(false);
            setErrors(errs as Partial<Record<keyof PrivateExerciseFormData, string>>);
          },
        }
      );
    }
  }

  async function uploadImage(file: File, exerciseId: number) {
    setUploadingImage(true);
    setImageError(null);
    try {
      const formData = new FormData();
      formData.append('image', file);
      const res = await axios.post(route('teacher.exercices.images.upload', exerciseId), formData);
      return res.data;
    } catch {
      setImageError("Erreur lors de l'upload.");
      return null;
    } finally {
      setUploadingImage(false);
    }
  }

  async function deleteImage(exerciseId: number, imageName: string) {
    await axios.delete(
      route('teacher.exercices.images.delete', { exercise: exerciseId, imageName })
    );
  }

  async function createTag(name: string, color?: string): Promise<TeacherTag | null> {
    try {
      const res = await axios.post(route('teacher.tags.store'), { name, color });
      return res.data as TeacherTag;
    } catch {
      return null;
    }
  }

  return {
    data,
    set,
    setFocusedField,
    processing,
    errors,
    submit,
    uploadImage,
    deleteImage,
    uploadingImage,
    imageError,
    createTag,
    pendingImageMap,
    addPendingImage,
    removePendingImage,
  };
}
