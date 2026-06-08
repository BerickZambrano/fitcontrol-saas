import ScrollReveal from "./ScrollReveal";

const pillars = [
  {
    num: "01",
    title: "Seguridad y Privacidad",
    desc: "Tus datos están protegidos con cifrado SSL de extremo a extremo y copias de seguridad automáticas diarias en la nube."
  },
  {
    num: "02",
    title: "Acceso Multiplataforma",
    desc: "Sincronización instantánea en tiempo real. Accede desde tu celular, tablet o computador como entrenador, directivo o acudiente."
  },
  {
    num: "03",
    title: "Cobranza Automatizada",
    desc: "Notificaciones y recordatorios automáticos de cobro que reducen la morosidad y garantizan la liquidez del club sin esfuerzo."
  }
];

const ScreenshotsSection = () => (
  <section className="py-24 bg-slate-950 relative overflow-hidden">
    {/* Decorative radial gradients */}
    <div className="absolute -bottom-48 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-primary/5 rounded-full blur-[120px] pointer-events-none" />

    <div className="container mx-auto px-6 lg:px-20 relative z-10">
      <ScrollReveal>
        <span className="text-xs uppercase tracking-widest text-primary font-bold mb-3 block">
          INFRAESTRUCTURA
        </span>
        <h2 className="text-4xl md:text-5xl font-black text-white leading-tight max-w-2xl">
          Tecnología de <span className="text-primary text-glow-neon">alto rendimiento.</span>
        </h2>
      </ScrollReveal>

      <div className="grid md:grid-cols-3 gap-6 mt-14">
        {pillars.map((p, i) => (
          <ScrollReveal key={p.num} delay={i * 150} direction="up">
            <div className="bg-white/[0.01] border border-white/5 backdrop-blur-md rounded-2xl p-8 hover:border-primary/25 hover:shadow-[0_0_30px_rgba(0,240,255,0.04)] hover:-translate-y-1.5 transition-all duration-300 h-full flex flex-col justify-between">
              <div>
                <p className="text-4xl font-black text-primary drop-shadow-[0_0_10px_rgba(0,240,255,0.3)] mb-6">
                  {p.num}
                </p>
                <h3 className="font-bold text-xl text-white mb-3">
                  {p.title}
                </h3>
                <p className="text-white/60 text-sm leading-relaxed">
                  {p.desc}
                </p>
              </div>
            </div>
          </ScrollReveal>
        ))}
      </div>
    </div>
  </section>
);

export default ScreenshotsSection;
