import ScrollReveal from "./ScrollReveal";

const stats = [
  { value: "100%", label: "Móvil first", desc: "Diseñado para tu celular" },
  { value: "GPS", label: "Integrado", desc: "Ubicación automática" },
  { value: "Gratis", label: "Para empezar", desc: "Sin tarjeta de crédito" },
];

const StatsBar = () => (
  <section className="bg-primary py-8">
    <div className="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
      {stats.map((s, i) => (
        <ScrollReveal key={s.value} delay={i * 100} duration={500}>
          <div>
            <p className="text-3xl md:text-4xl font-black text-primary-foreground">{s.value}</p>
            <p className="text-primary-foreground/90 font-semibold text-sm mt-1">{s.label}</p>
            <p className="text-primary-foreground/60 text-xs mt-0.5">{s.desc}</p>
          </div>
        </ScrollReveal>
      ))}
    </div>
  </section>
);

export default StatsBar;
