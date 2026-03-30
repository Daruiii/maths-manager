import { useState, useEffect, useCallback, useRef } from 'react';
import axios from 'axios';
import { route } from 'ziggy-js';
import { PickablePrivateExercise } from '@/types/models';
import { PaginatedResponse } from '@/types/api';
import { PrivateSort } from '@/types/ui';
import { INITIAL_PRIVATE_SORT } from '@/Constants/ds';

// ─── Types ────────────────────────────────────────────────────────────────────

interface Filters {
  search: string;
  type: 'basic' | 'problem' | '';
  difficulty: string;
}

const INITIAL_FILTERS: Filters = {
  search: '',
  type: '',
  difficulty: '',
};

// ─── Hook ─────────────────────────────────────────────────────────────────────

export function usePrivateExerciseSearch() {
  const [filters, setFilters] = useState<Filters>(INITIAL_FILTERS);
  const [sort, setSort] = useState<PrivateSort>(INITIAL_PRIVATE_SORT);
  const [exercises, setExercises] = useState<PickablePrivateExercise[]>([]);
  const [page, setPage] = useState(1);
  const [lastPage, setLastPage] = useState(1);
  const [total, setTotal] = useState(0);
  const [loading, setLoading] = useState(false);
  const [loadingMore, setLoadingMore] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const searchDebounceRef = useRef<ReturnType<typeof setTimeout> | null>(null);
  const filtersRef = useRef(filters);
  filtersRef.current = filters;
  const sortRef = useRef(sort);
  sortRef.current = sort;

  const fetchPage = useCallback(async (pageNum: number, append: boolean) => {
    const f = filtersRef.current;
    const s = sortRef.current;
    const params: Record<string, string | number> = { page: pageNum };
    if (f.search) params.search = f.search;
    if (f.type) params.type = f.type;
    if (f.difficulty) params.difficulty = f.difficulty;
    if (s.by) {
      params.sort_by = s.by;
      params.sort_dir = s.dir;
    }

    append ? setLoadingMore(true) : setLoading(true);
    try {
      const res = await axios.get<PaginatedResponse<PickablePrivateExercise>>(
        route('teacher.ds.builder.private'),
        { params }
      );
      const { data, last_page, total: t } = res.data;
      const enriched = data.map((e) => ({ ...e, kind: 'private' as const }));
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
  }, [filters, sort, fetchPage]);

  const loadMore = useCallback(() => {
    if (page < lastPage && !loadingMore) {
      fetchPage(page + 1, true);
    }
  }, [page, lastPage, loadingMore, fetchPage]);

  const setSearch = (search: string) => setFilters((f) => ({ ...f, search }));
  const setType = (type: Filters['type']) => setFilters((f) => ({ ...f, type }));
  const setDifficulty = (difficulty: string) => setFilters((f) => ({ ...f, difficulty }));
  const resetFilters = () => setFilters(INITIAL_FILTERS);

  const hasMore = page < lastPage;
  const hasActiveFilters = !!filters.search || !!filters.type || !!filters.difficulty;

  return {
    exercises,
    loading,
    loadingMore,
    hasMore,
    total,
    filters,
    sort,
    hasActiveFilters,
    error,
    loadMore,
    setSearch,
    setType,
    setDifficulty,
    setSort,
    resetFilters,
  };
}
