import { Check } from "lucide-react";
import { Button } from "./ui/button";
import ScrollReveal from "./ScrollReveal";

const plans = [
  {
    name: "MENSUAL", price: "$70.000", period: "/mes", desc: "Plan mensual flexible para tu club",
    features: ["Jugadores ilimitados", "Planificación de entrenamientos", "Gestión de partidos y asistencia", "Módulo de pagos y finanzas", "Reportes básicos", "Soporte por email"],
    cta: "Elegir Mensual", popular: false,
  },
  {
    name: "ANUAL", price: "$700.000", period: "/año", desc: "Ahorra 2 meses con pago anual",
    features: ["Todo lo del plan mensual", "Módulo avanzado de Árbitros", "Generación automática de Sanciones", "Historial médico y lesiones", "Reportes avanzados (Excel/PDF)", "Soporte prioritario 24/7"],
    cta: "Elegir Anual", popular: true,
  },
];

const PricingSection = ({ navigateWithSplash }) => {
  const handleLink = () => {
    if (navigateWithSplash) {
      navigateWithSplash("/onboarding");
    }
  };

  return (
    <section id="precios" className="py-20 bg-slate-950 relative overflow-hidden">
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-primary/5 rounded-full blur-[140px] pointer-events-none" />

      <div className="container mx-auto px-4 relative z-10">
        <ScrollReveal>
          <h2 className="text-3xl md:text-4xl font-black text-center text-white">Crece sin sorpresas</h2>
          <p className="text-center text-white/60 mt-3 max-w-2xl mx-auto text-lg">
            Empieza gratis. Escala cuando lo necesites. Sin contratos ni permanencia mínima.
          </p>
        </ScrollReveal>

        <div className="grid sm:grid-cols-2 gap-8 mt-14 max-w-3xl mx-auto">
          {plans.map((p, i) => (
            <ScrollReveal key={p.name} delay={i * 100}>
              <div
                className={`relative bg-white/[0.01] backdrop-blur-md rounded-2xl p-7 border flex flex-col h-full hover:-translate-y-1.5 transition-all duration-300 ${
                  p.popular
                    ? "border-primary glow-neon scale-[1.02] shadow-2xl shadow-primary/10"
                    : "border-white/5 shadow-sm hover:border-white/20"
                }`}
              >
                {p.popular && (
                  <span className="absolute -top-3 left-1/2 -translate-x-1/2 bg-primary text-black text-xs font-black px-4 py-1 rounded-full shadow-[0_0_15px_rgba(0,240,255,0.6)] tracking-wide">
                    MÁS POPULAR
                  </span>
                )}
                <p className="text-xs font-bold tracking-widest text-primary">{p.name}</p>
                <div className="mt-3 flex items-end gap-1">
                  <span className="text-3xl font-black text-white">{p.price}</span>
                  {p.period && <span className="text-white/60 text-sm mb-1">{p.period}</span>}
                </div>
                <p className="text-white/60 text-sm mt-2">{p.desc}</p>
                <ul className="mt-6 space-y-3 flex-1">
                  {p.features.map((f) => (
                    <li key={f} className="flex items-start gap-2 text-sm text-white/70">
                      <Check size={16} className="text-emerald-400 drop-shadow-[0_0_5px_rgba(16,185,129,0.3)] shrink-0 mt-0.5" /> {f}
                    </li>
                  ))}
                </ul>
                <Button
                  onClick={handleLink}
                  className={`mt-6 rounded-full w-full font-bold hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 ${
                    p.popular
                      ? "bg-primary text-black hover:glow-neon hover:shadow-primary/30"
                      : "bg-white/10 text-white border border-white/10 hover:bg-white/20"
                  }`}
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
