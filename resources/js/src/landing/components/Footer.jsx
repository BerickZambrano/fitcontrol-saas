import { Mail, Phone } from "lucide-react";

const Footer = ({ navigateWithSplash }) => {
  const handleLink = (e, url) => {
    if (navigateWithSplash) {
      e.preventDefault();
      navigateWithSplash(url);
    }
  };

  return (
    <footer id="contacto" className="bg-slate-950/60 backdrop-blur-md border-t border-white/5 py-16">
      <div className="container mx-auto px-4">
        <div className="grid md:grid-cols-4 gap-10">
          <div>
            <div className="flex items-center gap-2 mb-4">
              <img src="/images/logo.png" alt="FitControl" className="h-12 w-auto object-contain" />
            </div>
            <p className="text-white/50 text-sm leading-relaxed">
              Digitaliza la gestión de tu club de fútbol. Hecho para entrenadores y administradores deportivos.
            </p>
          </div>

          <div>
            <h4 className="text-white font-bold mb-4">Producto</h4>
            <ul className="space-y-2">
              <li><a href="#funciones" className="text-white/50 hover:text-white text-sm transition-colors">Funciones</a></li>
              <li><a href="#precios" className="text-white/50 hover:text-white text-sm transition-colors">Precios</a></li>
              <li><a href="#como-funciona" className="text-white/50 hover:text-white text-sm transition-colors">Cómo funciona</a></li>
            </ul>
          </div>

          <div>
            <h4 className="text-white font-bold mb-4">Cuenta</h4>
            <ul className="space-y-2">
              <li>
                <a
                  href="/login"
                  onClick={(e) => handleLink(e, "/login")}
                  className="text-white/50 hover:text-white text-sm transition-colors"
                >
                  Iniciar sesión
                </a>
              </li>
              <li>
                <a
                  href="/onboarding"
                  onClick={(e) => handleLink(e, "/onboarding")}
                  className="text-white/50 hover:text-white text-sm transition-colors"
                >
                  Solicitar acceso
                </a>
              </li>
            </ul>
          </div>

          <div>
            <h4 className="text-white font-bold mb-4">Soporte</h4>
            <div className="space-y-3">
              <a href="mailto:soporte@fitcontrol.app" className="flex items-center gap-2 text-white/50 hover:text-white text-sm transition-colors">
                <Mail size={16} className="text-primary" /> soporte@fitcontrol.app
              </a>
              <a href="tel:+573001234567" className="flex items-center gap-2 text-white/50 hover:text-white text-sm transition-colors">
                <Phone size={16} className="text-primary" /> +57 300 123 4567
              </a>
            </div>
          </div>
        </div>

        <div className="border-t border-white/5 mt-12 pt-6 flex flex-col md:flex-row justify-between items-center">
          <p className="text-white/40 text-sm">© 2026 FitControl. Todos los derechos reservados.</p>
          <div className="flex items-center gap-4 mt-2 md:mt-0">
            <a href="/terminos" target="_blank" rel="noopener noreferrer" className="text-white/40 hover:text-white text-sm transition-colors">
              Términos y Condiciones
            </a>
            <span className="text-white/20 hidden md:inline">•</span>
            <p className="text-white/40 text-sm">Hecho para clubes deportivos.</p>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
