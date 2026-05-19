import { HighlightStyle, StreamLanguage, syntaxHighlighting } from '@codemirror/language';
import { stex } from '@codemirror/legacy-modes/mode/stex';
import { tags } from '@lezer/highlight';
import { EditorView } from '@codemirror/view';
import { LatexField } from '@/types/models';

export interface LatexTabConfig {
  key: LatexField;
  label: string;
  placeholder: string;
  required?: boolean;
}

export interface LatexSnippetConfig {
  label: string;
  value: string;
}

export const LATEX_TABS: LatexTabConfig[] = [
  {
    key: 'latex_statement',
    label: 'Énoncé',
    placeholder: '\\text{Soit } f : x \\mapsto x^2...',
    required: true,
  },
  {
    key: 'latex_solution',
    label: 'Solution',
    placeholder: 'Solution LaTeX…',
  },
  {
    key: 'latex_clue',
    label: 'Indice',
    placeholder: 'Indice LaTeX…',
  },
];

export const LATEX_SNIPPETS: LatexSnippetConfig[] = [
  { label: '\\frac', value: '\\frac{a}{b}' },
  { label: '\\sqrt', value: '\\sqrt{x}' },
  { label: 'align*', value: '\\begin{align*}\n  \n\\end{align*}' },
];

export const LATEX_EXTENSION = StreamLanguage.define(stex);

export const CODEMIRROR_THEME = EditorView.theme({
  '&': {
    height: '100%',
    color: 'rgb(var(--text-color) / 1)',
    backgroundColor: 'rgb(var(--secondary-color) / 1)',
    fontFamily: 'var(--font-comfortaa), system-ui, sans-serif',
    fontSize: '0.875rem',
  },
  '.cm-scroller': {
    backgroundColor: 'rgb(var(--secondary-color) / 1)',
    fontFamily: 'inherit',
    lineHeight: '1.5',
  },
  '.cm-line': {
    color: 'rgb(var(--text-color) / 1)',
  },
  '.cm-content': {
    padding: '0.75rem',
    caretColor: 'rgb(var(--tertiary-color) / 1)',
  },
  '.cm-panels': {
    backgroundColor: 'rgb(var(--secondary-color) / 1)',
    color: 'rgb(var(--text-color) / 1)',
    borderBottom: '1px solid rgb(var(--border-color) / 1)',
  },
  '.cm-gutters': {
    backgroundColor: 'rgb(var(--secondary-color) / 1)',
    color: 'rgb(var(--text-gray) / 0.95)',
    borderRight: '1px solid rgb(var(--border-color) / 1)',
  },
  '.cm-activeLine': {
    backgroundColor: 'rgb(var(--primary-color) / 0.35)',
  },
  '.cm-activeLineGutter': {
    backgroundColor: 'rgb(var(--primary-color) / 0.5)',
  },
  '&.cm-focused': {
    outline: 'none',
    boxShadow: 'inset 0 0 0 1px rgb(var(--tertiary-color) / 0.5)',
  },
  '&.cm-focused .cm-selectionBackground, .cm-selectionBackground': {
    backgroundColor: 'rgb(var(--tertiary-color) / 0.35)',
  },
  '.cm-cursor, .cm-dropCursor': {
    borderLeftColor: 'rgb(var(--tertiary-color) / 1)',
  },
  '.cm-placeholder': {
    color: 'rgb(var(--text-gray) / 0.65)',
    fontStyle: 'italic',
  },
  '.cm-missing-graph-ref': {
    textDecorationLine: 'underline',
    textDecorationStyle: 'wavy',
    textDecorationColor: 'rgb(var(--error-color) / 1)',
    textDecorationThickness: '1.5px',
    textUnderlineOffset: '2px',
    cursor: 'help',
  },
});

export const CODEMIRROR_HIGHLIGHT = syntaxHighlighting(
  HighlightStyle.define([
    { tag: tags.keyword, color: 'rgb(var(--teacher-color) / 1)' },
    { tag: tags.string, color: 'rgb(var(--student-color) / 1)' },
    { tag: tags.number, color: 'rgb(var(--warning-color) / 1)' },
    { tag: tags.comment, color: 'rgb(var(--text-gray) / 0.75)', fontStyle: 'italic' },
    { tag: tags.variableName, color: 'rgb(var(--text-color) / 1)' },
    { tag: tags.operator, color: 'rgb(var(--tertiary-color) / 1)' },
    { tag: tags.bracket, color: 'rgb(var(--text-color) / 0.9)' },
  ])
);

export const LATEX_EDITOR_EXTENSIONS = [LATEX_EXTENSION, CODEMIRROR_THEME, CODEMIRROR_HIGHLIGHT];
