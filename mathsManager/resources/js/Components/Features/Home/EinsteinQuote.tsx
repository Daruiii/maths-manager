interface EinsteinQuoteProps {
  quote?: string;
  author?: string;
}

/**
 * A stylized Polaroid-like card for displaying famous quotes.
 */
export default function EinsteinQuote({
  quote = "L'imagination est plus importante que le savoir",
  author = 'Albert Einstein',
}: EinsteinQuoteProps) {
  return (
    <div className="mb-12 text-center">
      <div className="inline-block px-8 py-6 rounded-2xl bg-secondary-color/40 backdrop-blur-sm border border-border-color/60 shadow-xl transform rotate-1 hover:rotate-0 transition-transform duration-500">
        <blockquote className="text-xl md:text-2xl font-cmu-serif italic text-text-color text-center leading-relaxed">
          “{quote}”
        </blockquote>
        <cite className="block text-sm font-comfortaa mt-4 text-text-gray/80 text-right not-italic">
          — {author}
        </cite>
      </div>
    </div>
  );
}
