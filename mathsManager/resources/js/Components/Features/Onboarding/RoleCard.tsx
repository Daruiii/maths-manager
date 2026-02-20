import { ReactNode } from 'react';
import Button from '@/Components/Common/UI/Button';
import FeatureItem from '@/Components/Features/Onboarding/FeatureItem';
import { ROLE_THEME_CLASSES } from '@/Constants/onboarding';

export type RoleTheme = 'student' | 'teacher';

interface RoleCardProps {
  title: string;
  description: string;
  icon: ReactNode;
  features: string[];
  theme: RoleTheme;
  onClick: () => void;
  loading: boolean;
  buttonText: string;
}

export default function RoleCard({
  title,
  description,
  icon,
  features,
  theme,
  onClick,
  loading,
  buttonText,
}: RoleCardProps) {
  const classes = ROLE_THEME_CLASSES[theme];

  return (
    <div
      className={`relative group bg-secondary-color rounded-3xl p-1 shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden`}
    >
      {/* Gradient Background Animation */}
      <div
        className={`absolute inset-0 ${classes.gradientBgHover} via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500`}
      />

      <div
        className={`relative h-full bg-surface-color/50 backdrop-blur-sm rounded-[1.4rem] p-6 md:p-8 flex flex-col border border-border-color ${classes.borderHover} transition-colors duration-300`}
      >
        <div className="mb-6 md:mb-8 flex justify-between items-start">
          <div>
            <h3 className="text-xl md:text-2xl font-comfortaa-bold text-text-color mb-1 md:mb-2 text-left">
              {title}
            </h3>
            <p className="text-text-gray font-comfortaa text-xs md:text-sm text-left">
              {description}
            </p>
          </div>
          <div
            className={`flex shrink-0 h-14 w-14 md:h-16 md:w-16 items-center justify-center rounded-2xl ${classes.iconBg} ${classes.iconText} ${classes.rotation} transition-transform duration-300 shadow-inner`}
          >
            {icon}
          </div>
        </div>

        <ul className="mb-8 md:mb-10 space-y-3 md:space-y-4 flex-grow">
          {features.map((feature, idx) => (
            <FeatureItem key={idx} feature={feature} classes={classes} />
          ))}
        </ul>

        <Button
          onClick={onClick}
          isLoading={loading}
          variant="primary"
          size="md"
          className={`w-full ${classes.buttonBg} ${classes.buttonHover} ${classes.buttonShadow} border-0 shadow-lg mt-auto`}
        >
          {buttonText}
        </Button>
      </div>
    </div>
  );
}
