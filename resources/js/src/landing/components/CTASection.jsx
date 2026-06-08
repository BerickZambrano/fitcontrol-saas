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
    <section className="py-24 bg-gradient-to-br from-primary via-primary to-blue-700 relative overflow-hidden">
      <div className="container mx-auto px-4 relative z-10 text-center">
        <ScrollReveal>
          <div className="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center mx-auto mb-6">
            <Shield size={28} className="text-primary-foreground" />
          </div>
          <h2 className="text-3xl md:text-5xl font-black text-primary-foreground">
            ¿Listo para tomar el control?
          </h2>
          <p className="text-primary-foreground/80 mt-4 text-lg max-w-xl mx-auto">
            Únete a clubes que ya digitalizaron su gestión. Empieza gratis hoy, sin tarjeta de crédito.
          </p>
          <Button
            size="lg"
            onClick={handleLink}
            className="mt-8 rounded-full px-10 text-base font-semibold bg-primary-foreground text-primary hover:bg-primary-foreground/90 gap-2 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200"
          >
            Crear cuenta gratis <ArrowRight size={18} />
          </Button>
          <p className="text-primary-foreground/50 text-sm mt-4">
            Plan gratuito siempre disponible · Sin compromiso
          </p>
        </ScrollReveal>
      </div>
    </section>
  );
};

export default CTASection;
