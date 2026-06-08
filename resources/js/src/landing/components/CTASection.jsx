import { ArrowRight, Shield } from "lucide-react";
import { Button } from "./ui/button";
import ScrollReveal from "./ScrollReveal";

const CTASection = ({ navigateWithSplash }) => {
  const handleLink = () => {
    if (navigateWithSplash) {
      navigateWithSplash("/onboarding");
    }
  };

  return (
    <section className="py-24 bg-gradient-to-b from-slate-950 to-[#070b19] relative overflow-hidden border-t border-white/5">
      {/* Background Glow */}
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-primary/10 rounded-full blur-[120px] pointer-events-none" />

      <div className="container mx-auto px-4 relative z-10 text-center">
        <ScrollReveal>
          <div className="w-14 h-14 rounded-2xl bg-primary/5 border border-primary/20 flex items-center justify-center mx-auto mb-6 shadow-[0_0_20px_rgba(0,240,255,0.15)]">
            <Shield size={28} className="text-primary" />
          </div>
          <h2 className="text-3xl md:text-5xl font-black text-white">
            ¿Listo para tomar el control?
          </h2>
          <p className="text-white/70 mt-4 text-lg max-w-xl mx-auto">
            Únete a clubes que ya digitalizaron su gestión. Empieza gratis hoy, sin tarjeta de crédito.
          </p>
          <Button
            size="lg"
            onClick={handleLink}
            className="mt-8 rounded-full px-10 text-base font-bold bg-primary text-black hover:glow-neon hover:scale-[1.03] active:scale-[0.98] gap-2 transition-all duration-300"
          >
            Crear cuenta <ArrowRight size={18} />
          </Button>
          <p className="text-white/50 text-sm mt-4">
            Empieza ya
          </p>
        </ScrollReveal>
      </div>
    </section>
  );
};

export default CTASection;
