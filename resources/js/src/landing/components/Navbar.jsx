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

  const textClass = scrolled ? "text-foreground" : "text-hero-foreground";
  const textMutedClass = scrolled
    ? "text-muted-foreground hover:text-foreground"
    : "text-hero-foreground/70 hover:text-hero-foreground";

  return (
    <nav
      className={`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${
        scrolled
          ? "bg-background/90 backdrop-blur-md border-b border-border/40 shadow-sm"
          : "bg-transparent backdrop-blur-none border-b border-transparent"
      }`}
    >
      <div className="container mx-auto flex items-center justify-between h-16 px-4">
        <a href="#" className="flex items-center gap-2">
          <div className="w-9 h-9 rounded-lg bg-primary flex items-center justify-center">
            <span className="text-primary-foreground font-black text-lg">F</span>
          </div>
          <span className={`font-bold text-xl tracking-tight transition-colors ${textClass}`}>
            FitControl
          </span>
        </a>

        <div className="hidden md:flex items-center gap-8">
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

        <div className="hidden md:flex items-center gap-3">
          <a
            href="/login"
            onClick={(e) => handleLink(e, "/login")}
            className={`text-sm font-medium flex items-center gap-1.5 transition-colors ${textMutedClass}`}
          >
            <LogIn size={16} /> Iniciar sesión
          </a>
          <Button
            size="sm"
            className="rounded-full px-5"
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
          className={`md:hidden border-t px-4 pb-4 transition-colors ${
            scrolled ? "bg-background border-border/40" : "bg-hero border-white/10"
          }`}
        >
          {navLinks.map((l) => (
            <a
              key={l.href}
              href={l.href}
              onClick={() => setOpen(false)}
              className={`block py-3 text-sm font-medium border-b transition-colors ${
                scrolled
                  ? "text-foreground/80 hover:text-foreground border-border/30"
                  : "text-hero-foreground/80 hover:text-hero-foreground border-white/5"
              }`}
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
              className="rounded-full"
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
