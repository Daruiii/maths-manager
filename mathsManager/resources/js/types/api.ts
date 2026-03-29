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
  total: number;
}
