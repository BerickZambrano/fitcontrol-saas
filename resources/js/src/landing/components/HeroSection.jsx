import { ArrowRight } from "lucide-react";
import { Button } from "./ui/button";
import dashboardMockup from "../assets/dashboard-mockup.jpg";
import { useEffect, useState, useRef } from "react";

const HeroSection = ({ navigateWithSplash }) => {
  const [loaded, setLoaded] = useState(false);
  const videoRef = useRef(null);

  useEffect(() => {
    const t = setTimeout(() => setLoaded(true), 100);
    return () => clearTimeout(t);
  }, []);

  useEffect(() => {
    const video = videoRef.current;
    if (!video) return;

    let direction = -1; // Start in reverse (rotate left first)
    let intervalId = null;
    let initialized = false;

    const startReverse = () => {
      video.pause();
      if (intervalId) clearInterval(intervalId);
      
      intervalId = setInterval(() => {
        if (video.currentTime <= 0.4) {
          clearInterval(intervalId);
          intervalId = null;
          direction = 1;
          video.play().catch(() => {});
        } else {
          video.currentTime = Math.max(0, video.currentTime - 0.05);
        }
      }, 33);
    };

    const handleTimeUpdate = () => {
      if (video.duration && !isNaN(video.duration)) {
        if (!initialized) {
          initialized = true;
          video.currentTime = video.duration - 0.4;
          startReverse();
          return;
        }

        if (direction === 1 && video.currentTime >= video.duration - 0.4) {
          direction = -1;
          startReverse();
        }
      }
    };

    const handleLoadedMetadata = () => {
      if (!initialized && video.duration && !isNaN(video.duration)) {
        initialized = true;
        video.currentTime = video.duration - 0.4;
        startReverse();
      }
    };

    video.addEventListener("timeupdate", handleTimeUpdate);
    video.addEventListener("loadedmetadata", handleLoadedMetadata);

    if (video.duration && !isNaN(video.duration) && !initialized) {
      initialized = true;
      video.currentTime = video.duration - 0.4;
      startReverse();
    }

    return () => {
      video.removeEventListener("timeupdate", handleTimeUpdate);
      video.removeEventListener("loadedmetadata", handleLoadedMetadata);
      if (intervalId) clearInterval(intervalId);
    };
  }, []);

  const handleLink = (url) => {
    if (navigateWithSplash) {
      navigateWithSplash(url);
    }
  };

  return (
    <section className="relative bg-slate-950 pt-28 pb-16 md:pt-36 md:pb-24 overflow-hidden">
      <div className="absolute top-20 left-1/4 w-96 h-96 bg-primary/20 rounded-full blur-3xl" />
      <div className="absolute bottom-0 right-1/4 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl" />

      <div className="container mx-auto px-6 md:px-12 lg:px-20 relative z-10">
        <div className="grid lg:grid-cols-12 gap-12 items-center">
          <div className="lg:col-span-5 lg:pl-6 xl:pl-12">
            <h1
              className="text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight"
              style={{
                opacity: loaded ? 1 : 0,
                transform: loaded ? "translateY(0)" : "translateY(40px)",
                transition: "opacity 0.8s cubic-bezier(0.16,1,0.3,1), transform 0.8s cubic-bezier(0.16,1,0.3,1)",
              }}
            >
              Gestiona tu club de fútbol{" "}
              <span className="text-primary text-glow-neon block mt-2">como un profesional.</span>
            </h1>
            <p
              className="mt-6 text-white/70 text-lg md:text-xl max-w-lg leading-relaxed"
              style={{
                opacity: loaded ? 1 : 0,
                transform: loaded ? "translateY(0)" : "translateY(30px)",
                transition: "opacity 0.8s cubic-bezier(0.16,1,0.3,1) 0.15s, transform 0.8s cubic-bezier(0.16,1,0.3,1) 0.15s",
              }}
            >
              Jugadores, entrenamientos, partidos, pagos y estadísticas. Todo desde una sola plataforma diseñada para clubes deportivos.
            </p>
            <div
              className="flex flex-wrap gap-4 mt-8"
              style={{
                opacity: loaded ? 1 : 0,
                transform: loaded ? "translateY(0)" : "translateY(24px)",
                transition: "opacity 0.8s cubic-bezier(0.16,1,0.3,1) 0.3s, transform 0.8s cubic-bezier(0.16,1,0.3,1) 0.3s",
              }}
            >
              <Button
                size="lg"
                onClick={() => handleLink("/onboarding")}
                className="rounded-full px-8 text-base font-semibold gap-2 bg-primary text-primary-foreground hover:glow-neon hover:scale-[1.03] active:scale-[0.98] transition-all duration-300"
              >
                Probar gratis <ArrowRight size={18} />
              </Button>
              <Button
                size="lg"
                variant="outline"
                onClick={() => handleLink("/login")}
                className="rounded-full px-8 text-base font-semibold bg-transparent border-white/20 text-white hover:bg-white/10 hover:scale-[1.03] active:scale-[0.98] transition-all duration-300"
              >
                Iniciar sesión
              </Button>
            </div>
            <div
              className="flex gap-8 mt-10"
              style={{
                opacity: loaded ? 1 : 0,
                transition: "opacity 0.8s cubic-bezier(0.16,1,0.3,1) 0.45s",
              }}
            >
              <div>
                <p className="text-3xl font-black text-primary">1</p>
                <p className="text-white/50 text-sm">mes gratis</p>
              </div>
              <div>
                <p className="text-3xl font-black text-primary">5 min</p>
                <p className="text-white/50 text-sm">Para configurar</p>
              </div>
              <div>
                <p className="text-3xl font-black text-primary">24/7</p>
                <p className="text-white/50 text-sm">Soporte disponible</p>
              </div>
            </div>
          </div>

          <div
            className="animate-float lg:col-span-7"
            style={{
              opacity: loaded ? 1 : 0,
              transform: loaded ? "translateY(0) scale(1)" : "translateY(40px) scale(0.95)",
              transition: "opacity 1s cubic-bezier(0.16,1,0.3,1) 0.3s, transform 1s cubic-bezier(0.16,1,0.3,1) 0.3s",
            }}
          >
            <video
              autoPlay
              muted
              playsInline
              ref={videoRef}
              className="w-full object-cover aspect-video hover:scale-[1.01] transition-transform duration-500"
            >
              <source src="/videos/hero-video.webm" type="video/webm" />
              <source src="/videos/hero-video.mp4" type="video/mp4" />
            </video>
          </div>
        </div>
      </div>
    </section>
  );
};

export default HeroSection;
