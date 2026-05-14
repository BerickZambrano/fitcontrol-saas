import React, { useEffect, useState } from "react";

function SplashOverlay({ isVisible, targetUrl }) {
  const [progress, setProgress] = useState(0);
  const [logoVisible, setLogoVisible] = useState(false);
  const [textVisible, setTextVisible] = useState(false);
  const [fading, setFading] = useState(false);

  useEffect(() => {
    if (isVisible) {
      setLogoVisible(false);
      setTextVisible(false);
      setFading(false);
      setProgress(0);

      setTimeout(() => setLogoVisible(true), 300);
      setTimeout(() => setTextVisible(true), 800);

      const interval = setInterval(() => {
        setProgress((prev) => {
          if (prev >= 100) {
            clearInterval(interval);
            return 100;
          }
          return prev + 2;
        });
      }, 50);

      return () => clearInterval(interval);
    }
  }, [isVisible]);

  useEffect(() => {
    if (progress >= 100 && isVisible) {
      setTimeout(() => {
        setFading(true);
        setTimeout(() => {
          window.location.href = targetUrl;
        }, 500);
      }, 400);
    }
  }, [progress, isVisible, targetUrl]);

  if (!isVisible) return null;

  // Generate particles
  const particles = Array.from({ length: 30 }).map((_, i) => ({
    left: Math.random() * 100,
    duration: Math.random() * 3 + 2,
    delay: Math.random() * 3,
    size: Math.random() * 4 + 2,
  }));

  return (
    <div
      className="fixed inset-0 z-[9999] flex flex-col items-center justify-center overflow-hidden"
      style={{
        background: "linear-gradient(135deg, #0f2351 0%, #1e3a8a 50%, #0f2351 100%)",
        opacity: fading ? 0 : 1,
        transition: "opacity 0.5s ease",
      }}
    >
      {/* Particles */}
      {particles.map((p, i) => (
        <div
          key={i}
          className="absolute rounded-full"
          style={{
            left: `${p.left}%`,
            width: `${p.size}px`,
            height: `${p.size}px`,
            background: "rgba(59, 130, 246, 0.3)",
            animation: `float ${p.duration}s linear ${p.delay}s infinite`,
          }}
        />
      ))}

      {/* Logo */}
      <div
        className="relative z-10 flex flex-col items-center"
        style={{
          opacity: logoVisible ? 1 : 0,
          transform: logoVisible ? "scale(1)" : "scale(0.5)",
          transition: "all 0.8s cubic-bezier(0.34, 1.56, 0.64, 1)",
        }}
      >
        {/* Spinning ring */}
        <div
          className="absolute -inset-5 rounded-full border-2 border-blue-500/30 border-t-blue-500"
          style={{ animation: "spin 1.5s linear infinite" }}
        />
        <img
          src="/images/logo.png"
          alt="FitControl"
          className="w-48 h-48 object-contain"
          style={{ filter: "drop-shadow(0 0 40px rgba(59, 130, 246, 0.4))" }}
        />
      </div>

      {/* Text */}
      <div
        className="relative z-10 text-center mt-8"
        style={{
          opacity: textVisible ? 1 : 0,
          transform: textVisible ? "translateY(0)" : "translateY(20px)",
          transition: "all 0.6s ease 0.2s",
        }}
      >
        <h1 className="text-4xl font-extrabold text-white tracking-tight">
          Fit<span className="text-blue-500">Control</span>
        </h1>
        <p className="text-lg text-white/60 mt-2">
          Eleva tu entrenamiento al siguiente nivel
        </p>
      </div>

      {/* Progress bar */}
      <div
        className="relative z-10 mt-8 w-44 h-1 rounded-full overflow-hidden bg-white/10"
        style={{
          opacity: textVisible ? 1 : 0,
          transition: "opacity 0.4s ease",
        }}
      >
        <div
          className="h-full rounded-full"
          style={{
            width: `${progress}%`,
            background: "linear-gradient(90deg, #2563eb, #3b82f6, #60a5fa)",
            boxShadow: "0 0 12px rgba(59, 130, 246, 0.5)",
            transition: "width 0.05s linear",
          }}
        />
      </div>

      {/* Keyframes */}
      <style>{`
        @keyframes float {
          0% { transform: translateY(100vh) scale(0); opacity: 0; }
          10% { opacity: 1; }
          90% { opacity: 1; }
          100% { transform: translateY(-100px) scale(1); opacity: 0; }
        }
        @keyframes spin {
          to { transform: rotate(360deg); }
        }
      `}</style>
    </div>
  );
}

export default SplashOverlay;
