import React, { useEffect, useState } from "react";

function SplashOverlay({ isVisible, targetUrl }) {
  const [fading, setFading] = useState(false);

  useEffect(() => {
    if (isVisible) {
      setFading(false);
      
      // Navigate to target URL after 1200ms (900ms show + 300ms transition)
      const timeout = setTimeout(() => {
        setFading(true);
        setTimeout(() => {
          window.location.href = targetUrl;
        }, 300);
      }, 900);

      return () => clearTimeout(timeout);
    }
  }, [isVisible, targetUrl]);

  if (!isVisible) return null;

  return (
    <div
      className="fixed inset-0 z-[9999] flex flex-col items-center justify-center overflow-hidden"
      style={{
        backdropFilter: "blur(24px)",
        WebkitBackdropFilter: "blur(24px)",
        backgroundColor: "transparent",
        opacity: fading ? 0 : 1,
        transition: "opacity 0.3s ease",
      }}
    >
      <div className="relative w-16 h-16">
        {/* Spinner */}
        <div
          className="absolute inset-0 rounded-full border-2 border-white/10 border-t-blue-500"
          style={{
            animation: "spin 1s cubic-bezier(0.5, 0.1, 0.5, 0.9) infinite",
          }}
        />
        {/* Pulsing logo */}
        <img
          src="/images/logo.png"
          alt="Cargando..."
          className="absolute inset-3 w-10 h-10 object-contain opacity-80"
          style={{
            animation: "pulseSoft 2s ease-in-out infinite",
          }}
        />
      </div>

      <style>{`
        @keyframes spin {
          to { transform: rotate(360deg); }
        }
        @keyframes pulseSoft {
          0%, 100% { transform: scale(1); opacity: 0.7; }
          50% { transform: scale(1.05); opacity: 0.95; }
        }
      `}</style>
    </div>
  );
}

export default SplashOverlay;
