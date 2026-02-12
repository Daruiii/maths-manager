/**
 * Interface for the individual stat items displayed in the grid.
 */
interface StatItemProps {
  label: string;
  value: number | string;
  icon: React.ReactNode;
  color: string;
}

/**
 * A colorful card representing a single metric with an icon.
 */
export default function StatCard({ label, value, icon, color }: StatItemProps) {
  return (
    <div
      className={`${color} p-4 rounded-2xl text-white shadow-lg shadow-black/5 hover:scale-105 transition-transform`}
    >
      <div className="flex justify-between items-start mb-2">
        <span className="opacity-80">{icon}</span>
        <span className="text-2xl font-comfortaa-bold">{value}</span>
      </div>
      <p className="text-[10px] uppercase font-comfortaa-bold tracking-wider opacity-90">{label}</p>
    </div>
  );
}
