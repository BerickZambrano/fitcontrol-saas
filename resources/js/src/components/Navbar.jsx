import React, { useState } from "react";
import logo from "../assets/logosf.png";
import "../assets/hamburgers.css";
import nuevouser from "../assets/nuevouser.svg";
import candado from "../assets/candado.svg";

function Navbar() {
  const [isNavActive, setIsNavActive] = useState(false);

  const handleNavButtonClick = () => {
    document.body.style.overflow = isNavActive ? "auto" : "hidden";
    setIsNavActive(!isNavActive);
  };

  return (
    <div className="h-[80px] flex justify-between items-center gap-6" role="navigation">
      {/* Logo */}
      <div className="h-16 cursor-pointer">
        <a href="/" className="flex items-center gap-2">
          <img src={logo} alt="FitControl" className="h-35 w-35" />
          <span className="text-2xl font-bold text-[#121c4c] tracking-wider"></span>
        </a>
      </div>

      {/* Desktop Menu */}
      <ul className="flex items-center gap-[36px] text-[#12092a] font-bold tracking-wide max-[950px]:hidden">
        <li className="hover:text-[#485179] transition-colors duration-300">
          <a href="/onboarding">ACCEDE</a>
        </li>
        <li className="hover:text-[#485179] transition-colors duration-300">
          <a href="/solicitar-acceso">SOLICITAR ACCESO</a>
        </li>
        <li className="hover:text-[#485179] transition-colors duration-300">
          <a href="#work">APLICATIVO</a>
        </li>
        <li className="hover:text-[#485179] transition-colors duration-300">
          <a href="#pricing">PRECIOS</a>
        </li>
        <li className="hover:text-[#485179] transition-colors duration-300">
          <a href="#faq">PQR's</a>
        </li>
      </ul>

      {/* CTA Buttons */}
      <a
        href="/login"
        className="h-11 px-6 text-black font-bold tracking-wider rounded-lg flex items-center hover:bg-[#ffff] duration-150 border-2 border-[#121c4c] active:scale-95 transition-all max-[950px]:hidden"
      >
        <img src={candado} width={"30px"} alt="" />
        INICIA SESIÓN
      </a>

      <a
        href="/solicitar-acceso"
        className="h-11 px-6 text-white font-bold tracking-wider bg-[#121c4c] rounded-lg flex items-center hover:bg-[#485179] duration-150 border-2 border-[#121c4c] active:scale-95 transition-all max-[950px]:hidden"
      >
        <img src={nuevouser} width={"30px"} alt="" />
        CREAR CUENTA
      </a>

      {/* Hamburger */}
      <button
        type="button"
        className={`hamburger hamburger--emphatic ${isNavActive ? "is-active" : ""} min-[950px]:hidden`}
        onClick={handleNavButtonClick}
      >
        <span className="hamburger-box">
          <span className="hamburger-inner"></span>
        </span>
      </button>

      {/* Mobile Menu */}
      {isNavActive && (
        <div className="min-h-[100%] w-full bg-[#d3cae0] absolute top-[80px] left-0 min-[950px]:hidden">
          <ul className="flex flex-col items-center gap-8 text-2xl font-bold text-[#12092a] tracking-wider mt-16 pb-20">
            <li className="hover:text-[#121c4c]" onClick={handleNavButtonClick}>
              <a href="/login">INICIAR SESIÓN</a>
            </li>
            <li className="hover:text-[#121c4c]" onClick={handleNavButtonClick}>
              <a href="/solicitar-acceso">SOLICITAR ACCESO</a>
            </li>
            <li className="hover:text-[#121c4c]" onClick={handleNavButtonClick}>
              <a href="#work">APLICATIVO</a>
            </li>
            <li className="hover:text-[#121c4c]" onClick={handleNavButtonClick}>
              <a href="#pricing">PRECIOS</a>
            </li>
            <li className="hover:text-[#121c4c]" onClick={handleNavButtonClick}>
              <a href="#faq">PQR's</a>
            </li>
          </ul>
        </div>
      )}
    </div>
  );
}

export default Navbar;