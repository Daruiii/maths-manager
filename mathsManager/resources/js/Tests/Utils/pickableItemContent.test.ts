import { describe, expect, it } from 'vitest';
import { getRenderablePickableContent, normalizeStoragePath } from '@/Utils/pickableItemContent';
import { PickableProblem, PickableExercise } from '@/types/models';

describe('pickableItemContent', () => {
  it('normalizes relative storage paths', () => {
    expect(normalizeStoragePath('problems/image-1.png')).toBe('/storage/problems/image-1.png');
    expect(normalizeStoragePath('storage/problems/image-2.png')).toBe(
      '/storage/problems/image-2.png'
    );
    expect(normalizeStoragePath('/storage/problems/image-3.png')).toBe(
      '/storage/problems/image-3.png'
    );
    expect(normalizeStoragePath('https://cdn.example.com/image-4.png')).toBe(
      'https://cdn.example.com/image-4.png'
    );
    expect(normalizeStoragePath('public/storage/problems/image-5.png')).toBe(
      '/storage/problems/image-5.png'
    );
    expect(normalizeStoragePath('problems\\image-6.png')).toBe('/storage/problems/image-6.png');
  });

  it('prefers statement html for problems and still maps images', () => {
    const problem: PickableProblem = {
      kind: 'problem',
      id: 1,
      name: 'P1',
      difficulty: 3,
      time: 20,
      harder_exercise: false,
      type: null,
      year: null,
      academy: null,
      multiple_chapter_id: 1,
      multiple_chapter: {
        id: 1,
        title: 'Chapitre',
        theme: 'Algebre',
        classe_id: 1,
      },
      statement: '<p>HTML rendu</p>',
      latex_statement: 'latex fallback',
      image_paths: { img1: 'problems/a.png' },
    };

    const result = getRenderablePickableContent(problem);

    expect(result.statementHtml).toBe('<p>HTML rendu</p>');
    expect(result.latexStatement).toBe('latex fallback');
    expect(result.images).toEqual({ img1: '/storage/problems/a.png' });
  });

  it('returns latex content for exercise when available', () => {
    const exercise: PickableExercise = {
      kind: 'exercise',
      id: 2,
      name: 'E1',
      difficulty: 2,
      order: 1,
      subchapter_id: 1,
      subchapter: {
        id: 1,
        title: 'Sub',
        chapter: { id: 1, title: 'Chap' },
      },
      latex_statement: 'x^2',
      image_paths: null,
    };

    const result = getRenderablePickableContent(exercise);

    expect(result.statementHtml).toBeNull();
    expect(result.latexStatement).toBe('x^2');
    expect(result.images).toEqual({});
  });

  it('supports image_paths provided as JSON string map', () => {
    const exercise = {
      kind: 'exercise',
      id: 3,
      name: 'E2',
      difficulty: 1,
      order: 2,
      subchapter_id: 1,
      subchapter: {
        id: 1,
        title: 'Sub',
        chapter: { id: 1, title: 'Chap' },
      },
      latex_statement: '\\graph{img-1}{0.5}{demo}',
      image_paths: JSON.stringify({
        'img-1': 'public/storage/exercises/exercise-1-statement/img-1.png',
      }),
    } as unknown as PickableExercise;

    const result = getRenderablePickableContent(exercise);

    expect(result.images).toEqual({
      'img-1': '/storage/exercises/exercise-1-statement/img-1.png',
    });
  });
});
