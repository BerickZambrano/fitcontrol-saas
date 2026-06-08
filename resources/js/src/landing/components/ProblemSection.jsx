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
  <section id="como-funciona" className="py-20 bg-surface-warm">
    <div className="container mx-auto px-4">
      <ScrollReveal>
        <h2 className="text-3xl md:text-4xl font-black text-center text-foreground">
          ¿Te suena familiar?
        </h2>
        <p className="text-center text-muted-foreground mt-3 max-w-2xl mx-auto text-lg">
          La mayoría de clubes deportivos siguen gestionándose con herramientas obsoletas. FitControl cambia eso.
        </p>
      </ScrollReveal>

      <div className="grid md:grid-cols-2 gap-8 mt-12 max-w-4xl mx-auto">
        <ScrollReveal delay={100} direction="left">
          <div className="bg-card rounded-2xl p-8 border border-destructive/20 shadow-sm h-full">
            <h3 className="font-bold text-lg text-destructive mb-4 flex items-center gap-2">
              <AlertTriangle size={20} /> Sin FitControl
            </h3>
            <ul className="space-y-3">
              {problems.map((p) => (
                <li key={p} className="flex items-start gap-3 text-muted-foreground">
                  <span className="w-1.5 h-1.5 rounded-full bg-destructive mt-2 shrink-0" />
                  {p}
                </li>
              ))}
            </ul>
          </div>
        </ScrollReveal>

        <ScrollReveal delay={250} direction="right">
          <div className="bg-card rounded-2xl p-8 border border-success/20 shadow-sm h-full">
            <h3 className="font-bold text-lg text-success mb-4 flex items-center gap-2">
              <CheckCircle size={20} /> Con FitControl
            </h3>
            <ul className="space-y-3">
              {solutions.map((s) => (
                <li key={s} className="flex items-start gap-3 text-muted-foreground">
                  <CheckCircle size={16} className="text-success mt-0.5 shrink-0" />
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
