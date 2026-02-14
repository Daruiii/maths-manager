import Logo from '@/Components/Common/UI/Logo';

export default function HeaderLogo() {
  return (
    <div className="flex items-center gap-10">
      <a
        href="/"
        className="flex items-center gap-3 hover:opacity-80 transition shrink-0 translate-y-[-2px]"
      >
        <Logo showBadge={true} size="md" />
      </a>
    </div>
  );
}
