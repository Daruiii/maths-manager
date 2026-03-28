import { useState, useEffect, useCallback, useRef } from 'react';
import axios from 'axios';
import { route } from 'ziggy-js';
import { PickableExercise } from '@/types/models';

interface PaginatedResponse {
  data: PickableExercise[];
  current_page: number;
  last_page: number;
  total: number;
}

interface Filters {
  search: string;
  subchapterId: string;
  difficulty: string;
  classId: string;
}

const INITIAL_FILTERS: Filters = {
  search: '',
  subchapterId: '',
  difficulty: '',
  classId: '',
};

export function useExerciseSearch() {
  const [filters, setFilters] = useState<Filters>(INITIAL_FILTERS);
  const [exercises, setExercises] = useState<PickableExercise[]>([]);
  const [page, setPage] = useState(1);
  const [lastPage, setLastPage] = useState(1);
  const [total, setTotal] = useState(0);
  const [loading, setLoading] = useState(false);
  const [loadingMore, setLoadingMore] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const searchDebounceRef = useRef<ReturnType<typeof setTimeout> | null>(null);
  const filtersRef = useRef(filters);
  filtersRef.current = filters;

  const fetchPage = useCallback(async (pageNum: number, append: boolean) => {
    const f = filtersRef.current;
    const params: Record<string, string | number> = { page: pageNum };
    if (f.search) params.search = f.search;
    if (f.subchapterId) params.subchapter_id = f.subchapterId;
    if (f.difficulty) params.difficulty = f.difficulty;
    if (f.classId) params.class_id = f.classId;

    append ? setLoadingMore(true) : setLoading(true);
    try {
      const res = await axios.get<PaginatedResponse>(route('teacher.ds.builder.exercises'), {
        params,
      });
      const { data, last_page, total: t } = res.data;
      const enriched = data.map((e) => ({ ...e, kind: 'exercise' as const }));
      setExercises((prev) => (append ? [...prev, ...enriched] : enriched));
      setLastPage(last_page);
      setTotal(t);
      setPage(pageNum);
      setError(null);
    } catch (err) {
      if (axios.isAxiosError(err)) {
        setError(err.response?.status ? `Erreur ${err.response.status}` : 'Erreur réseau');
      } else {
        setError('Erreur inconnue');
      }
    } finally {
      append ? setLoadingMore(false) : setLoading(false);
    }
  }, []);

  useEffect(() => {
    if (searchDebounceRef.current) clearTimeout(searchDebounceRef.current);
    searchDebounceRef.current = setTimeout(() => {
      fetchPage(1, false);
    }, 300);
    return () => {
      if (searchDebounceRef.current) clearTimeout(searchDebounceRef.current);
    };
  }, [filters, fetchPage]);

  const loadMore = useCallback(() => {
    if (page < lastPage && !loadingMore) {
      fetchPage(page + 1, true);
    }
  }, [page, lastPage, loadingMore, fetchPage]);

  const setSearch = (search: string) => setFilters((f) => ({ ...f, search }));
  const setSubchapterId = (subchapterId: string) => setFilters((f) => ({ ...f, subchapterId }));
  const setDifficulty = (difficulty: string) => setFilters((f) => ({ ...f, difficulty }));
  const setClassId = (classId: string) => setFilters((f) => ({ ...f, classId }));
  const resetFilters = () => setFilters(INITIAL_FILTERS);

  const hasMore = page < lastPage;
  const hasActiveFilters =
    !!filters.search || !!filters.subchapterId || !!filters.difficulty || !!filters.classId;

  return {
    exercises,
    loading,
    loadingMore,
    hasMore,
    total,
    filters,
    hasActiveFilters,
    error,
    loadMore,
    setSearch,
    setSubchapterId,
    setDifficulty,
    setClassId,
    resetFilters,
  };
}
