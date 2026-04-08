import React from "react";

function ContactUs() {
  return (
    <div className="flex justify-center">
      <div className="p-10 px-6 w-full max-w-[1440px] flex flex-col justify-center items-center gap-4">

        <h3 className="text-2xl font-bold inline-block text-center border-t-[#121c4c] border-2 pt-10 text-[#12092a]">
          ¡Hey! ¿Necesitas un plan personalizado?{" "}
          <span className="text-[#121c4c]">Contáctanos</span>
        </h3>

        <p className="text-[#12092a] tracking-wide text-center">
          Prueba FitControl gratis durante 7 días. Si no te convence, puedes cancelar sin compromiso.
        </p>

        <button
          type="button"
          className="h-12 px-8 mt-7 text-white uppercase text-sm font-bold tracking-wider bg-[#121c4c] rounded-lg flex items-center hover:bg-[#485179] duration-150 border-2 border-[#121c4c] active:scale-95 transition-all"
        >
          COMIENZA
        </button>

      </div>
    </div>
  );
}

export default ContactUs;