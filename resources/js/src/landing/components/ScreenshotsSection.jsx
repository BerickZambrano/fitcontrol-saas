import dashboardMockup from "../assets/dashboard-mockup.jpg";
import ScrollReveal from "./ScrollReveal";

const ScreenshotsSection = () => (
  <section className="py-20 bg-hero overflow-hidden">
    <div className="container mx-auto px-4">
      <ScrollReveal>
        <h2 className="text-3xl md:text-4xl font-black text-center text-hero-foreground">
          Conoce la plataforma
        </h2>
        <p className="text-center text-hero-foreground/60 mt-3 max-w-2xl mx-auto text-lg">
          Diseñada para ser intuitiva. Gestiona tu club desde cualquier dispositivo.
        </p>
      </ScrollReveal>

      <div className="mt-14 flex justify-center">
        <ScrollReveal delay={150} className="max-w-4xl w-full">
          <img
            src={dashboardMockup}
            alt="Panel administrativo de FitControl"
            loading="lazy"
            width={1280}
            height={800}
            className="rounded-2xl border border-white/10 shadow-2xl shadow-primary/20 w-full"
          />
          <p className="text-hero-foreground/80 font-semibold text-center mt-4">Panel administrativo</p>
        </ScrollReveal>
      </div>
    </div>
  </section>
);

export default ScreenshotsSection;
