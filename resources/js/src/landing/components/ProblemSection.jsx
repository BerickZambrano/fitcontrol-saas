import { AlertTriangle, CheckCircle } from "lucide-react";
import ScrollReveal from "./ScrollReveal";

const problems = [
  "Listas de jugadores en papel o WhatsApp",
  "Sin control de pagos ni cobros pendientes",
  "Entrenamientos desorganizados sin seguimiento",
  "Estadísticas inexistentes o manuales",
];

const solutions = [
  "Base de datos digital de jugadores y equipos",
  "Control automático de pagos y morosos",
  "Planificación de entrenamientos con asistencia",
  "Estadísticas en tiempo real y reportes",
];

const ProblemSection = () => (
  <section id="como-funciona" className="relative py-20 bg-slate-950 overflow-hidden">
    {/* Background glow */}
    <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-primary/5 rounded-full blur-3xl pointer-events-none" />

    <div className="container mx-auto px-4 relative z-10">
      <ScrollReveal>
        <h2 className="text-3xl md:text-4xl font-black text-center text-white">
          ¿Te suena familiar?
        </h2>
        <p className="text-center text-white/60 mt-3 max-w-2xl mx-auto text-lg">
          La mayoría de clubes deportivos siguen gestionándose con herramientas obsoletas. FitControl cambia eso.
        </p>
      </ScrollReveal>

      <div className="grid md:grid-cols-2 gap-8 mt-12 max-w-4xl mx-auto">
        <ScrollReveal delay={100} direction="left">
          <div className="bg-white/[0.01] border border-red-500/10 hover:border-red-500/20 backdrop-blur-md rounded-2xl p-8 shadow-sm transition-all duration-300 h-full">
            <h3 className="font-bold text-lg text-red-400 mb-4 flex items-center gap-2">
              <AlertTriangle size={20} className="text-red-400" /> Sin FitControl
            </h3>
            <ul className="space-y-3">
              {problems.map((p) => (
                <li key={p} className="flex items-start gap-3 text-white/60">
                  <span className="w-1.5 h-1.5 rounded-full bg-red-400 mt-2 shrink-0" />
                  {p}
                </li>
              ))}
            </ul>
          </div>
        </ScrollReveal>

        <ScrollReveal delay={250} direction="right">
          <div className="bg-white/[0.01] border border-primary/15 hover:border-primary/30 hover:shadow-[0_0_30px_rgba(0,240,255,0.08)] backdrop-blur-md rounded-2xl p-8 shadow-sm transition-all duration-300 h-full">
            <h3 className="font-bold text-lg text-primary mb-4 flex items-center gap-2 drop-shadow-[0_0_10px_rgba(0,240,255,0.2)]">
              <CheckCircle size={20} className="text-primary" /> Con FitControl
            </h3>
            <ul className="space-y-3">
              {solutions.map((s) => (
                <li key={s} className="flex items-start gap-3 text-white/80">
                  <CheckCircle size={16} className="text-primary mt-0.5 shrink-0" />
                  {s}
                </li>
              ))}
            </ul>
          </div>
        </ScrollReveal>
      </div>
    </div>
  </section>
);

export default ProblemSection;
