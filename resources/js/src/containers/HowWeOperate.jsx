import React from "react";
import { operatingSteps } from "../scripts/operatingSteps";
import OperatingStepsCard from "../components/OperatingStepsCard";

function HowWeOperate() {
  return (
    <div className="flex justify-center" id="about">
      <div className="py-20 px-6 w-full max-w-[1440px] flex flex-col justify-center items-center gap-5">

        <h2 className="text-[#12092a] font-bold text-4xl capitalize text-center max-w-[1000px] max-[768px]:text-3xl">
          La plataforma que impulsa el{" "}
          <span className="text-[#121c4c] capitalize">crecimiento</span> de tu{" "}
          <span className="text-[#121c4c] capitalize">club deportivo</span>
        </h2>

        <p className="text-[#12092a] text-center max-w-[850px]">
          Desde la gestión de jugadores y entrenadores hasta la organización de
          entrenamientos, torneos y pagos, FitControl entiende las necesidades
          reales de los clubes deportivos y ofrece una solución práctica,
          eficiente y accesible para administrar todo en un solo lugar.
        </p>

        <div className="mt-10 w-full flex items-start justify-center gap-8 flex-wrap max-[450px]:mt-6">
          {operatingSteps.map((data) => (
            <OperatingStepsCard key={data.title} {...data} />
          ))}
        </div>

      </div>
    </div>
  );
}

export default HowWeOperate;