interface Props {
  paths: string[];
  label?: string;
}

function privateUrl(path: string): string {
  const [context, identifier, ...rest] = path.split('/');
  return route('private.file.serve', { context, identifier, filename: rest.join('/') });
}

export default function PictureGrid({ paths, label = 'Photo' }: Props) {
  if (paths.length === 0) return null;

  return (
    <div className="grid grid-cols-2 sm:grid-cols-3 gap-2">
      {paths.map((path, index) => {
        const url = privateUrl(path);

        return (
          <a
            key={`${path}-${index}`}
            href={url}
            target="_blank"
            rel="noreferrer"
            className="block aspect-square rounded-xl overflow-hidden border border-border-color hover:opacity-80 transition-opacity"
          >
            <img src={url} alt={`${label} ${index + 1}`} className="w-full h-full object-cover" />
          </a>
        );
      })}
    </div>
  );
}
