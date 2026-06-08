import { Users, Calendar, Trophy, BarChart3, CreditCard, ClipboardCheck } from "lucide-react";
import ScrollReveal from "./ScrollReveal";

const features = [
  { icon: Users, title: "Gestión de jugadores", desc: "Fichas completas con datos personales, posición, categoría y estado de cada jugador." },
  { icon: Calendar, title: "Control de entrenamientos", desc: "Planifica sesiones, registra asistencia y haz seguimiento del progreso de cada equipo." },
  { icon: Trophy, title: "Programación de partidos", desc: "Organiza calendario de partidos, convocatorias y resultados en un solo lugar." },
  { icon: BarChart3, title: "Estadísticas deportivas", desc: "Goles, asistencias, tarjetas y rendimiento individual y grupal en tiempo real." },
  { icon: CreditCard, title: "Gestión de pagos", desc: "Controla mensualidades, inscripciones y morosos. Genera reportes financieros." },
  { icon: ClipboardCheck, title: "Control de asistencia", desc: "Registro digital de asistencia con historial y notificaciones automáticas." },
];

const FeaturesSection = () => (
  <section id="funciones" className="py-20 bg-background">
    <div className="container mx-auto px-4">
      <ScrollReveal>
        <h2 className="text-3xl md:text-4xl font-black text-center text-foreground">
          Todo en un solo lugar
        </h2>
        <p className="text-center text-muted-foreground mt-3 max-w-2xl mx-auto text-lg">
          Desde la inscripción del jugador hasta el cobro de mensualidades, FitControl cubre cada paso de tu club.
        </p>
      </ScrollReveal>

      <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-14">
        {features.map((f, i) => (
          <ScrollReveal key={f.title} delay={i * 100}>
            <div className="bg-surface-warm rounded-2xl p-7 hover:shadow-lg hover:-translate-y-1.5 hover:shadow-primary/10 transition-all duration-300 group h-full">
              <div className="w-12 h-12 rounded-xl bg-accent flex items-center justify-center mb-5 group-hover:bg-primary group-hover:text-primary-foreground transition-all duration-300 group-hover:scale-110">
                <f.icon size={24} className="text-primary group-hover:text-primary-foreground transition-colors" />
              </div>
              <h3 className="font-bold text-lg text-foreground">{f.title}</h3>
              <p className="text-muted-foreground mt-2 text-sm leading-relaxed">{f.desc}</p>
            </div>
          </ScrollReveal>
        ))}
      </div>
    </div>
  </section>
);

export default FeaturesSection;
