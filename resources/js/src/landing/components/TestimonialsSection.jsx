import { Star } from "lucide-react";
import ScrollReveal from "./ScrollReveal";

const testimonials = [
  {
    name: "Carlos Mendoza", role: "Director técnico, Escuela FC Halcones",
    text: "FitControl nos permitió organizar toda la información de nuestros 120 jugadores. Ahora el control de asistencia y pagos es automático.", stars: 5,
  },
  {
    name: "Laura Gómez", role: "Administradora, Club Deportivo Tigres",
    text: "Antes usábamos Excel y WhatsApp para todo. FitControl nos ahorró horas de trabajo cada semana.", stars: 5,
  },
  {
    name: "Andrés Salazar", role: "Entrenador, Academia Fútbol Total",
    text: "Las estadísticas de rendimiento me ayudan a tomar mejores decisiones en los entrenamientos y convocatorias.", stars: 5,
  },
];

const TestimonialsSection = () => (
  <section className="py-20 bg-slate-950 relative overflow-hidden">
    <div className="container mx-auto px-4">
      <ScrollReveal>
        <h2 className="text-3xl md:text-4xl font-black text-center text-white">
          Lo que dicen nuestros usuarios
        </h2>
      </ScrollReveal>
      <div className="grid md:grid-cols-3 gap-8 mt-14">
        {testimonials.map((t, i) => (
          <ScrollReveal key={t.name} delay={i * 120}>
            <div className="bg-white/[0.01] backdrop-blur-md rounded-2xl p-7 border border-white/5 shadow-sm hover:border-white/15 hover:-translate-y-1.5 hover:shadow-[0_0_30px_rgba(0,240,255,0.03)] transition-all duration-300 h-full">
              <div className="flex gap-0.5 mb-4">
                {Array.from({ length: t.stars }).map((_, j) => (
                  <Star key={j} size={16} className="fill-amber-400 text-amber-400 drop-shadow-[0_0_5px_rgba(245,158,11,0.4)]" />
                ))}
              </div>
              <p className="text-white/70 text-sm leading-relaxed italic">"{t.text}"</p>
              <div className="mt-5">
                <p className="font-bold text-white text-sm">{t.name}</p>
                <p className="text-white/50 text-xs">{t.role}</p>
              </div>
            </div>
          </ScrollReveal>
        ))}
      </div>
    </div>
  </section>
);

export default TestimonialsSection;
