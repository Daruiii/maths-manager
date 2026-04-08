import { useState, useEffect, useCallback, useRef } from 'react';
import axios from 'axios';
import { route } from 'ziggy-js';
import { PickableProblem } from '@/types/models';
import { PaginatedResponse } from '@/types/api';
import { ProblemSort } from '@/types/ui';
import { INITIAL_DM_PROBLEM_SORT } from '@/Constants/dm';

interface Filters {
  search: string;
  chapterId: string;
  classId: string;
  difficulty: string;
  harder: boolean;
  year: string;
  academy: string;
}

const INITIAL_FILTERS: Filters = {
  search: '',
  chapterId: '',
  classId: '',
  difficulty: '',
  harder: false,
  year: '',
  academy: '',
};

export function useDMProblemSearch() {
  const [filters, setFilters] = useState<Filters>(INITIAL_FILTERS);
  const [sort, setSort] = useState<ProblemSort>(INITIAL_DM_PROBLEM_SORT);
  const [problems, setProblems] = useState<PickableProblem[]>([]);
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
    if (f.chapterId) params.chapter_id = f.chapterId;
    if (f.classId) params.class_id = f.classId;
    if (f.difficulty) params.difficulty = f.difficulty;
    if (f.harder) params.harder = '1';
    if (f.year) params.year = f.year;
    if (f.academy) params.academy = f.academy;
    if (s.by) {
      params.sort_by = s.by;
      params.sort_dir = s.dir;
    }

    append ? setLoadingMore(true) : setLoading(true);
    try {
      const res = await axios.get<PaginatedResponse<PickableProblem>>(
        route('teacher.dm.builder.problems'),
        { params }
      );
      const { data, last_page, total: t } = res.data;
      const enriched = data.map((p) => ({ ...p, kind: 'problem' as const }));
      setProblems((prev) => (append ? [...prev, ...enriched] : enriched));
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
  const setChapterId = (chapterId: string) => setFilters((f) => ({ ...f, chapterId }));
  const setClassId = (classId: string) => setFilters((f) => ({ ...f, classId }));
  const setDifficulty = (difficulty: string) => setFilters((f) => ({ ...f, difficulty }));
  const setHarder = (harder: boolean) => setFilters((f) => ({ ...f, harder }));
  const setYear = (year: string) => setFilters((f) => ({ ...f, year }));
  const setAcademy = (academy: string) => setFilters((f) => ({ ...f, academy }));
  const resetFilters = () => setFilters(INITIAL_FILTERS);

  const hasMore = page < lastPage;
  const hasActiveFilters =
    !!filters.search ||
    !!filters.chapterId ||
    !!filters.classId ||
    !!filters.difficulty ||
    filters.harder ||
    !!filters.year ||
    !!filters.academy;

  return {
    problems,
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
    setChapterId,
    setClassId,
    setDifficulty,
    setHarder,
    setYear,
    setAcademy,
    setSort,
    resetFilters,
  };
}
