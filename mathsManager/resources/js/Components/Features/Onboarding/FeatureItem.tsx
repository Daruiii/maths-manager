import { Check } from 'lucide-react';

interface FeatureItemProps {
  feature: string;
  classes: {
    checkBg: string;
    checkText: string;
  };
}

export default function FeatureItem({ feature, classes }: FeatureItemProps) {
  return (
    <li className="flex items-center gap-3 text-text-color font-comfortaa">
      <div
        className={`flex-shrink-0 h-5 w-5 md:h-6 md:w-6 rounded-full ${classes.checkBg} flex items-center justify-center`}
      >
        <Check size={14} className={classes.checkText} />
      </div>
      <span className="text-xs md:text-sm text-left">{feature}</span>
    </li>
  );
}
