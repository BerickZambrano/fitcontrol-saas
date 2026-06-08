import { Users, Calendar, Trophy, CreditCard, Scale, HeartPulse } from "lucide-react";
import ScrollReveal from "./ScrollReveal";

const features = [
  { icon: Users, title: "Gestión de jugadores", desc: "Fichas completas con perfiles físicos, posición, categoría y estado de cada jugador." },
  { icon: Calendar, title: "Entrenamientos y asistencia", desc: "Planifica sesiones de práctica y registra la asistencia digital de entrenadores y jugadores." },
  { icon: Trophy, title: "Partidos y convocatorias", desc: "Organiza el calendario, gestiona alineaciones, convoca jugadores (filtrando sancionados) y registra resultados." },
  { icon: Scale, title: "Módulo de árbitros y sanciones", desc: "Panel para árbitros externos, registro de novedades post-partido y cálculo automático de suspensiones." },
  { icon: HeartPulse, title: "Historial médico y lesiones", desc: "Seguimiento médico de jugadores, registro de tratamientos, lesiones activas e historial de altas." },
  { icon: CreditCard, title: "Gestión de pagos y finanzas", desc: "Controla mensualidades, cobros por inscripciones, seguimiento a morosos y reportes financieros." },
];

const FeaturesSection = () => (
  <section id="funciones" className="py-20 bg-slate-950 relative overflow-hidden">
    <div className="absolute top-0 right-1/4 w-96 h-96 bg-primary/5 rounded-full blur-3xl pointer-events-none" />

    <div className="container mx-auto px-4 relative z-10">
      <ScrollReveal>
        <h2 className="text-3xl md:text-4xl font-black text-center text-white">
          Todo en un solo lugar
        </h2>
        <p className="text-center text-white/60 mt-3 max-w-2xl mx-auto text-lg">
          Desde la inscripción del jugador hasta el cobro de mensualidades, FitControl cubre cada paso de tu club.
        </p>
      </ScrollReveal>

      <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-14">
        {features.map((f, i) => (
          <ScrollReveal key={f.title} delay={i * 100}>
            <div className="bg-white/[0.01] border border-white/5 hover:border-primary/20 rounded-2xl p-7 hover:shadow-[0_0_30px_rgba(0,240,255,0.05)] hover:-translate-y-1.5 transition-all duration-300 group h-full">
              <div className="w-12 h-12 rounded-xl bg-primary/5 border border-primary/15 flex items-center justify-center mb-5 group-hover:bg-primary group-hover:border-primary transition-all duration-300 group-hover:scale-110 group-hover:shadow-[0_0_15px_rgba(0,240,255,0.4)]">
                <f.icon size={24} className="text-primary group-hover:text-black transition-colors" />
              </div>
              <h3 className="font-bold text-lg text-white">{f.title}</h3>
              <p className="text-white/60 mt-2 text-sm leading-relaxed">{f.desc}</p>
            </div>
          </ScrollReveal>
        ))}
      </div>
    </div>
  </section>
);

export default FeaturesSection;
