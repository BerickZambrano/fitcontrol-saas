import { Check } from "lucide-react";
import { Button } from "./ui/button";
import ScrollReveal from "./ScrollReveal";

const plans = [
  {
    name: "GRATIS", price: "Gratis", period: "", desc: "Demo de 7 días para probar la aplicación",
    features: ["1 usuario", "Hasta 30 jugadores", "Gestión básica de equipos", "7 días de prueba"],
    cta: "Empezar gratis", popular: false,
  },
  {
    name: "BÁSICO", price: "$29.900", period: "/mes", desc: "Plan mensual para empezar",
    features: ["Hasta 3 usuarios", "Jugadores ilimitados", "Entrenamientos y partidos", "Reportes básicos", "Soporte por email"],
    cta: "Elegir Básico", popular: true,
  },
  {
    name: "CLUB", price: "$49.900", period: "/mes", desc: "Para clubes en crecimiento",
    features: ["Hasta 10 usuarios", "Jugadores ilimitados", "Estadísticas avanzadas", "Gestión de pagos", "Soporte prioritario"],
    cta: "Elegir Club", popular: false,
  },
  {
    name: "PRO", price: "$79.900", period: "/mes", desc: "Para clubes grandes y academias",
    features: ["Usuarios ilimitados", "Jugadores ilimitados", "Reportes avanzados", "API y exportación", "Soporte dedicado"],
    cta: "Elegir Pro", popular: false,
  },
];

const PricingSection = ({ navigateWithSplash }) => {
  const handleLink = () => {
    if (navigateWithSplash) {
      navigateWithSplash("/onboarding");
    }
  };

  return (
    <section id="precios" className="py-20 bg-surface-warm">
      <div className="container mx-auto px-4">
        <ScrollReveal>
          <h2 className="text-3xl md:text-4xl font-black text-center text-foreground">Crece sin sorpresas</h2>
          <p className="text-center text-muted-foreground mt-3 max-w-2xl mx-auto text-lg">
            Empieza gratis. Escala cuando lo necesites. Sin contratos ni permanencia mínima.
          </p>
        </ScrollReveal>

        <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-14">
          {plans.map((p, i) => (
            <ScrollReveal key={p.name} delay={i * 100}>
              <div
                className={`relative bg-card rounded-2xl p-7 border flex flex-col h-full hover:-translate-y-1.5 hover:shadow-xl transition-all duration-300 ${
                  p.popular ? "border-primary shadow-xl shadow-primary/10 scale-[1.02]" : "border-border shadow-sm"
                }`}
              >
                {p.popular && (
                  <span className="absolute -top-3 left-1/2 -translate-x-1/2 bg-primary text-primary-foreground text-xs font-bold px-4 py-1 rounded-full">
                    Más Popular
                  </span>
                )}
                <p className="text-xs font-bold tracking-widest text-primary">{p.name}</p>
                <div className="mt-3 flex items-end gap-1">
                  <span className="text-3xl font-black text-foreground">{p.price}</span>
                  {p.period && <span className="text-muted-foreground text-sm mb-1">{p.period}</span>}
                </div>
                <p className="text-muted-foreground text-sm mt-2">{p.desc}</p>
                <ul className="mt-6 space-y-3 flex-1">
                  {p.features.map((f) => (
                    <li key={f} className="flex items-center gap-2 text-sm text-muted-foreground">
                      <Check size={16} className="text-success shrink-0" /> {f}
                    </li>
                  ))}
                </ul>
                <Button
                  onClick={handleLink}
                  className={`mt-6 rounded-full w-full hover:shadow-lg transition-all duration-200 ${p.popular ? "hover:shadow-primary/30" : "bg-foreground text-background hover:bg-foreground/90"}`}
                  variant={p.popular ? "default" : "secondary"}
                >
                  {p.cta}
                </Button>
              </div>
            </ScrollReveal>
          ))}
        </div>
      </div>
    </section>
  );
};

export default PricingSection;
