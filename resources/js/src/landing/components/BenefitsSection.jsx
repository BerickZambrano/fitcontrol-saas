import { Clock, FolderKanban, Shield, TrendingUp } from "lucide-react";
import ScrollReveal from "./ScrollReveal";

const benefits = [
  { icon: Clock, title: "Ahorra tiempo", desc: "Automatiza tareas repetitivas y enfócate en lo que importa: entrenar." },
  { icon: FolderKanban, title: "Mejor organización", desc: "Toda la información de tu club centralizada y accesible 24/7." },
  { icon: Shield, title: "Control total", desc: "Visualiza pagos, asistencia y rendimiento desde un solo dashboard." },
  { icon: TrendingUp, title: "Profesionaliza tu club", desc: "Da una imagen profesional con reportes y gestión digital moderna." },
];

const BenefitsSection = () => (
  <section className="py-20 bg-slate-950 relative overflow-hidden">
    <div className="container mx-auto px-4">
      <ScrollReveal>
        <h2 className="text-3xl md:text-4xl font-black text-center text-white">
          ¿Por qué elegir FitControl?
        </h2>
      </ScrollReveal>
      <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 mt-14">
        {benefits.map((b, i) => (
          <ScrollReveal key={b.title} delay={i * 120}>
            <div className="text-center group">
              <div className="w-14 h-14 rounded-2xl bg-primary/5 border border-primary/15 flex items-center justify-center mx-auto mb-4 group-hover:bg-primary group-hover:border-primary transition-all duration-300 group-hover:scale-110 group-hover:shadow-[0_0_20px_rgba(0,240,255,0.4)]">
                <b.icon size={28} className="text-primary group-hover:text-black transition-colors" />
              </div>
              <h3 className="font-bold text-white text-lg">{b.title}</h3>
              <p className="text-white/60 text-sm mt-2 leading-relaxed">{b.desc}</p>
            </div>
          </ScrollReveal>
        ))}
      </div>
    </div>
  </section>
);

export default BenefitsSection;
