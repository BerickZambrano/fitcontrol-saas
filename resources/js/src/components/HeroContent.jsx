import React from "react";
import ShortCheckPointer from "./ShortCheckPointer";
import devsTeam from "../assets/ilu1.svg";

function HeroContent() {
  return (
    <div
      className="flex justify-center items-center flex-col w-full max-[768px]:gap-2"
      role="main"
    >
      <h1 className="max-w-[985px] mt-[50px] text-center text-[#12092a] font-bold text-5xl capitalize max-[768px]:text-4xl max-[450px]:text-2xl">
        Una plataforma de gestión deportiva tan completa que tu club no querrá volver{" "}
        <span className="text-[#121c4c]">atrás</span>.{" "}
        Accede a nuestro sistema{" "}
        <span className="text-[#485179]">FitControl</span>.
      </h1>

      <div className="mt-[40px] flex justify-center items-center gap-10 max-[768px]:flex-wrap max-[768px]:gap-2">
        <ShortCheckPointer text={"Todo tu club en un solo sistema"} />
        <ShortCheckPointer text={"Automatiza tu gestión deportiva"} />
        <ShortCheckPointer text={"Simple, rápido y profesional"} />
      </div>

      <button
        type="button"
        className="mt-6 uppercase text-white font-bold px-6 py-3 bg-gradient-to-r from-[#121c4c] to-[#485179] rounded-lg hover:from-[#12092a] hover:to-[#121c4c] duration-200 active:scale-95 transition-all shadow-lg"
      >
        Solicita FitControl
      </button>

      <img
        src={devsTeam}
        alt="Equipo deportivo utilizando la plataforma FitControl"
        className="w-[700px] mt-20 max-[768px]:w-[90%]"
      />
    </div>
  );
}

export default HeroContent;