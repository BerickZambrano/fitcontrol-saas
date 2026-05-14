import React from "react";
import personIcon from "../assets/user.svg";
import startQuote from "../assets/comi1.svg";
import endQuote from "../assets/comi2.svg";

function TestimonialSlider() {
  return (
    <div className="max-w-[1000px] flex flex-col items-center justify-center">

      <div className="mb-8 flex items-center justify-center gap-4 relative">

        <img
          src={startQuote}
          alt="Start Quote Mark"
          className="absolute w-[50px] h-[50px] top-[-16px] left-[-60px] max-[900px]:w-[32px] max-[900px]:h-[32px] max-[900px]:left-[-28px]"
        />

        <img
          src={endQuote}
          alt="End Quote Mark"
          className="absolute w-[50px] h-[50px] bottom-[-24px] right-5 max-[900px]:w-[32px] max-[900px]:h-[32px] max-[900px]:bottom-[-8px]"
        />

        <p className="max-w-[700px] text-white text-center font-bold text-3xl max-[900px]:text-2xl max-[900px]:max-w-[500px] max-[768px]:text-xl max-[450px]:text-lg">
          FitControl nos ayudó a identificar los principales problemas en la administración de nuestro club y a implementar una gestión más organizada y fácil de usar. Gracias a la plataforma, optimizamos nuestros procesos internos y mejoramos el control de jugadores, pagos y entrenamientos.
        </p>

      </div>

      <div className="mb-6 flex items-center text-white gap-4">
        <img src={personIcon} alt="Person Icon" className="w-[50px] h-[50px]" />

        <div>
          <p className="text-[#485179] font-bold uppercase text-lg">
            Berick Zambrano
          </p>
          <p className="text-sm text-[#d3cae0]">
            Cliente de nuestro servicio
          </p>
        </div>
      </div>

      <button
        type="button"
        className="h-12 px-8 mt-2 text-white uppercase text-sm font-bold tracking-wider bg-[#121c4c] rounded-lg flex items-center hover:bg-[#485179] duration-150 border-2 border-[#121c4c] active:scale-95 transition-all"
      >
        Explora nuestros casos reales
      </button>

    </div>
  );
}

export default TestimonialSlider;