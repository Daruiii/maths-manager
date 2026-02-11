import React from 'react';

interface AdminStatCardProps {
  title: string;
  count: number | string;
  icon: React.ReactNode;
  color: string;
  href: string;
}

/**
 * A colorful summary card for admin metrics.
 */
export default function AdminStatCard({ title, count, icon, color, href }: AdminStatCardProps) {
  return (
    <a 
      href={href} 
      className={`bg-white p-6 rounded-3xl border-l-8 ${color} shadow-sm hover:shadow-md transition group shadow-lg shadow-black/[0.02]`}
    >
        <div className="flex justify-between items-center">
            <div className="space-y-1">
                <p className="text-xs font-comfortaa text-text-gray group-hover:text-text-color transition">{title}</p>
                <p className="text-3xl font-comfortaa-bold">{count}</p>
            </div>
            <div className="p-3 bg-gray-50 rounded-2xl group-hover:scale-110 transition-transform text-text-color">
                {icon}
            </div>
        </div>
    </a>
  );
}
