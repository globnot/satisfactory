import React from 'react';

const Logo = ({ className = 'w-6 h-6' }) => {
  return (
    <svg
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 100 100"
      className={`${className} stroke-current text-text dark:text-darkText`} // Classe dynamique Tailwind pour le thème
    >
      <path
        d="M10 30 Q10 10 30 10 L70 10 Q90 10 90 30 L90 70 Q90 90 70 90 L30 90 Q10 90 10 70 Z"
        strokeWidth="6"
        fill="none"
      />
      <path
        d="M20 80 Q50 50 80 20"
        strokeWidth="6"
        fill="none"
      />
    </svg>
  );
};

export default Logo;