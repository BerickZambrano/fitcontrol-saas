import { ArrowRight } from "lucide-react";
import { Button } from "./ui/button";
import dashboardMockup from "../assets/dashboard-mockup.jpg";
import { useEffect, useState } from "react";

const HeroSection = ({ navigateWithSplash }) => {
  const [loaded, setLoaded] = useState(false);
  useEffect(() => {
    const t = setTimeout(() => setLoaded(true), 100);
    return () => clearTimeout(t);
  }, []);

  const handleLink = (url) => {
    if (navigateWithSplash) {
      navigateWithSplash(url);
    }
  };

  return (
    <section className="relative bg-hero pt-28 pb-16 md:pt-36 md:pb-24 overflow-hidden">
      <div className="absolute top-20 left-1/4 w-96 h-96 bg-primary/20 rounded-full blur-3xl" />
      <div className="absolute bottom-0 right-1/4 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl" />

      <div className="container mx-auto px-4 relative z-10">
        <div className="grid lg:grid-cols-2 gap-12 items-center">
          <div>
            <h1
              className="text-4xl md:text-5xl lg:text-6xl font-black text-hero-foreground leading-tight"
              style={{
                opacity: loaded ? 1 : 0,
                transform: loaded ? "translateY(0)" : "translateY(40px)",
                transition: "opacity 0.8s cubic-bezier(0.16,1,0.3,1), transform 0.8s cubic-bezier(0.16,1,0.3,1)",
              }}
            >
              Gestiona tu club de fútbol{" "}
              <span className="text-gradient">como un profesional.</span>
            </h1>
            <p
              className="mt-6 text-hero-foreground/70 text-lg md:text-xl max-w-lg leading-relaxed"
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
                className="rounded-full px-8 text-base font-semibold gap-2 hover:shadow-lg hover:shadow-primary/30 hover:-translate-y-0.5 transition-all duration-200"
              >
                Probar gratis <ArrowRight size={18} />
              </Button>
              <Button
                size="lg"
                variant="outline"
                onClick={() => handleLink("/login")}
                className="rounded-full px-8 text-base font-semibold bg-transparent border-white/20 text-hero-foreground hover:bg-white/10 hover:text-hero-foreground hover:-translate-y-0.5 transition-all duration-200"
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
                <p className="text-3xl font-black text-primary">100%</p>
                <p className="text-hero-foreground/50 text-sm">Gratis para empezar</p>
              </div>
              <div>
                <p className="text-3xl font-black text-primary">5 min</p>
                <p className="text-hero-foreground/50 text-sm">Para configurar</p>
              </div>
              <div>
                <p className="text-3xl font-black text-primary">24/7</p>
                <p className="text-hero-foreground/50 text-sm">Soporte disponible</p>
              </div>
            </div>
          </div>

          <div
            className="animate-float"
            style={{
              opacity: loaded ? 1 : 0,
              transform: loaded ? "translateY(0) scale(1)" : "translateY(40px) scale(0.95)",
              transition: "opacity 1s cubic-bezier(0.16,1,0.3,1) 0.3s, transform 1s cubic-bezier(0.16,1,0.3,1) 0.3s",
            }}
          >
            <img
              src={dashboardMockup}
              alt="Panel de control FitControl - gestión de clubes de fútbol"
              width={1280}
              height={800}
              className="rounded-2xl shadow-2xl shadow-primary/20 border border-white/10"
            />
          </div>
        </div>
      </div>
    </section>
  );
};

export default HeroSection;
