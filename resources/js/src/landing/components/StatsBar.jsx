import ScrollReveal from "./ScrollReveal";

const stats = [
  { value: "24/7", label: "Acceso en la nube", desc: "Gestiona tu club desde cualquier lugar" },
  { value: "2FA", label: "Seguridad integrada", desc: "Doble factor para tu acceso" },
  { value: "Multi-Tenant", label: "Club independiente", desc: "Datos 100% aislados y seguros" },
];

const StatsBar = () => (
  <section className="bg-slate-950 border-y border-white/5 py-8">
    <div className="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
      {stats.map((s, i) => (
        <ScrollReveal key={s.value} delay={i * 100} duration={500}>
          <div>
            <p className="text-3xl md:text-4xl font-black text-primary drop-shadow-[0_0_15px_rgba(0,240,255,0.3)]">{s.value}</p>
            <p className="text-white font-semibold text-sm mt-1">{s.label}</p>
            <p className="text-white/55 text-xs mt-0.5">{s.desc}</p>
          </div>
        </ScrollReveal>
      ))}
    </div>
  </section>
);

export default StatsBar;
