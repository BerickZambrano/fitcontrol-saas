import { useEffect, useState } from "react";
import { Menu, X, LogIn } from "lucide-react";
import { Button } from "./ui/button";

const navLinks = [
  { label: "Funciones", href: "#funciones" },
  { label: "Cómo funciona", href: "#como-funciona" },
  { label: "Precios", href: "#precios" },
  { label: "Contacto", href: "#contacto" },
];

const Navbar = ({ navigateWithSplash }) => {
  const [open, setOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);

  useEffect(() => {
    const handleScroll = () => setScrolled(window.scrollY > 40);
    window.addEventListener("scroll", handleScroll, { passive: true });
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  const handleLink = (e, url) => {
    if (navigateWithSplash) {
      e.preventDefault();
      navigateWithSplash(url);
    }
  };

  const textClass = "text-white";
  const textMutedClass = "text-white/70 hover:text-white";

  return (
    <nav
      className={`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${
        scrolled
          ? "bg-black/60 backdrop-blur-md border-b border-white/10 shadow-lg"
          : "bg-transparent backdrop-blur-none border-b border-transparent"
      }`}
    >
      <div className="container mx-auto flex items-center justify-between h-20 md:h-24 px-6 md:px-12 lg:px-20 transition-all duration-300">
        <a href="#" className="flex items-center">
          <img src="/images/logo.png" alt="FitControl" className="h-24 md:h-30 w-auto object-contain" />
        </a>

        <div className="hidden md:flex items-center gap-14 lg:gap-20">
          {navLinks.map((l) => (
            <a
              key={l.href}
              href={l.href}
              className={`text-sm font-medium transition-colors ${textMutedClass}`}
            >
              {l.label}
            </a>
          ))}
        </div>

        <div className="hidden md:flex items-center gap-4">
          <a
            href="/login"
            onClick={(e) => handleLink(e, "/login")}
            className={`text-sm font-medium flex items-center gap-1.5 transition-colors ${textMutedClass}`}
          >
            <LogIn size={16} /> Iniciar sesión
          </a>
          <Button
            size="sm"
            className="rounded-full px-5 bg-primary text-primary-foreground font-semibold hover:glow-neon hover:scale-[1.03] active:scale-[0.98] transition-all duration-300"
            onClick={(e) => handleLink(e, "/onboarding")}
          >
            Solicitar acceso
          </Button>
        </div>

        <button
          className={`md:hidden transition-colors ${textClass}`}
          onClick={() => setOpen(!open)}
        >
          {open ? <X size={24} /> : <Menu size={24} />}
        </button>
      </div>

      {open && (
        <div
          className="md:hidden border-t px-4 pb-4 transition-all duration-300 bg-black/95 border-white/10"
        >
          {navLinks.map((l) => (
            <a
              key={l.href}
              href={l.href}
              onClick={() => setOpen(false)}
              className="block py-3 text-sm font-medium border-b border-white/5 transition-colors text-white/80 hover:text-white"
            >
              {l.label}
            </a>
          ))}
          <div className="flex flex-col gap-2 mt-4">
            <a
              href="/login"
              onClick={(e) => handleLink(e, "/login")}
              className={`text-sm font-medium flex items-center gap-1.5 ${textMutedClass}`}
            >
              <LogIn size={16} /> Iniciar sesión
            </a>
             <Button
              size="sm"
              className="rounded-full bg-primary text-primary-foreground font-semibold hover:glow-neon transition-all duration-300"
              onClick={(e) => handleLink(e, "/onboarding")}
            >
              Solicitar acceso
            </Button>
          </div>
        </div>
      )}
    </nav>
  );
};

export default Navbar;
