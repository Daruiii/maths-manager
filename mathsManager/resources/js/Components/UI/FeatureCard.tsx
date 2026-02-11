import React from 'react';

interface FeatureCardProps {
  title: string;
  desc: string;
  icon: string | React.ReactNode;
}

/**
 * A simple card displaying a feature with an emoji icon and description.
 */
export default function FeatureCard({ title, desc, icon }: FeatureCardProps) {
  return (
    <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-50 hover:border-admin-color/30 transition-colors group">
      <div className="text-3xl mb-4 group-hover:scale-110 transition-transform inline-block">
        {icon}
      </div>
      <h3 className="text-lg font-comfortaa-bold mb-2 text-text-color">{title}</h3>
      <p className="text-sm text-text-gray font-comfortaa">{desc}</p>
    </div>
  );
}
