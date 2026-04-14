/**
 * API Contracts
 * Types des réponses backend — DTOs entre le serveur et le frontend.
 * Ne pas y mettre d'entités Eloquent directes (→ models.ts).
 */

/** Réponse paginée standard Laravel */
export interface PaginatedResponse<T> {
  data: T[];
  current_page: number;
  last_page: number;
  per_page?: number;
  total: number;
}

export type BureauActivityType =
  | 'ds_assigned'
  | 'td_assigned'
  | 'dm_assigned'
  | 'student_joined'
  | 'invitation_configured'
  | 'correction_requested'
  | 'correction_processed';

export type BureauActivityScope = 'assignments' | 'students' | 'corrections';

export interface BureauActivity {
  id: string;
  type: BureauActivityType;
  scope: BureauActivityScope;
  title: string;
  description: string;
  occurred_at: string | null;
}

export interface BureauHistoryFilters {
  search: string;
  scope: 'all' | BureauActivityScope;
  type: 'all' | BureauActivityType;
  sort: 'asc' | 'desc';
  per_page: number;
}

// ─── Catalogue (dropdowns classification) ─────────────────────────────────────

/** Sous-ensemble allégé de Classe pour les selects/filtres */
export interface CatalogueClasse {
  id: number;
  name: string;
}

/** Chapitre pour les selects/filtres (différent de MultipleChapter qui est pour les DS) */
export interface CatalogueChapter {
  id: number;
  title: string;
  class_id: number;
}

/** Sous-chapitre pour les selects/filtres */
export interface CatalogueSubchapter {
  id: number;
  title: string;
  chapter_id: number;
}
