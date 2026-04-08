import React from "react";
import { signingUpSteps } from "../scripts/signingUpSteps";
import SignUpStepsCard from "../components/SignUpStepsCard";

function HowWeWork() {
  return (
    <div className="flex justify-center" id="operations">
      <div className="py-10 px-6 pb-20 max-w-[1440px] flex flex-col justify-center items-center gap-10 max-[768px]:gap-8">

        <h2 className="text-[#12092a] font-bold text-4xl capitalize text-center max-w-[1000px] max-[768px]:text-3xl">
          Cómo <span className="text-[#121c4c] capitalize">empezar</span> con{" "}
          <span className="text-[#121c4c] capitalize">FitControl</span>
        </h2>

        <p className="text-[#12092a] text-center max-w-[1000px]">
          Imagina tu club completamente organizado: jugadores registrados,
          entrenamientos programados y pagos controlados en un solo lugar.
          FitControl simplifica la gestión deportiva con una plataforma
          práctica, rápida y accesible para que puedas enfocarte en el
          crecimiento y rendimiento de tu equipo.
        </p>

        <div className="mt-10 w-full flex items-start justify-center gap-8 flex-wrap max-[768px]:mt-6">
          {signingUpSteps.map((data, index) => (
            <SignUpStepsCard key={data.title} index={index + 1} {...data} />
          ))}
        </div>

      </div>
    </div>
  );
}

export default HowWeWork;