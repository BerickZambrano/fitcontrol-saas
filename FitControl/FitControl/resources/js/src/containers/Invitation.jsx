import React from "react";
import foundersTeam from "../assets/ilu5.svg";

function Invitation() {
  return (
    <div className="bg-[#F6F6F6] flex justify-center">
      <div className="w-full max-w-[1440px] px-6 flex flex-col justify-center items-center gap-8">

        <h3 className="mt-16 capitalize font-bold text-5xl max-w-[600px] text-center max-[768px]:text-3xl text-[#12092a]">
          Llevemos tu <span className="text-[#121c4c]">club</span> al siguiente{" "}
          <span className="text-[#121c4c]">nivel</span>
        </h3>

        <p className="text-sm text-[#12092a] max-[768px]:text-center">
          Prueba FitControl gratis durante 7 días. Sin riesgos y con cancelación en cualquier momento.
        </p>

        <a
          href="/solicitar-acceso"
          className="h-12 px-14 mt-2 text-white text-sm font-bold tracking-wider bg-[#121c4c] rounded-lg flex items-center justify-center hover:bg-[#485179] duration-150 border-2 border-[#121c4c] active:scale-95 transition-all"
        >
          SOLICITA TU DEMO
        </a>

        <img
          src={foundersTeam}
          alt="Equipo deportivo utilizando FitControl"
          className="w-[600px]"
        />

      </div>
    </div>
  );
}

export default Invitation;