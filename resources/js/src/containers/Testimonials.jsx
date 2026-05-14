import React from "react";
import testimonialImg1 from "../assets/1.png";
import testimonialImg2 from "../assets/2.png";
import testimonialImg3 from "../assets/3.png";
import TestimonialSlider from "../components/TestimonialSlider";

function Testimonials() {
  return (
    <div className="bg-[#12092a] flex justify-center" id="work">
      <div className="pt-20 pb-12 px-6 w-full max-w-[1440px] flex flex-col items-center gap-14 max-[900px]:gap-10">

        <h3 className="max-w-[1000px] capitalize font-bold text-4xl text-white text-center max-[768px]:text-3xl">
          Cómo <span className="text-[#485179]">FitControl</span> está modernizando la
          gestión de los <span className="text-[#485179]">clubes deportivos</span>
        </h3>

        <div className="max-w-[1100px] px-16 flex flex-col gap-10 max-[900px]:px-0">
          <img
            src={testimonialImg1}
            alt="Panel de gestión deportiva en FitControl"
            className="w-full"
          />
          <img
            src={testimonialImg2}
            alt="Administración de jugadores y entrenamientos"
            className="w-full"
          />
          <img
            src={testimonialImg3}
            alt="Control de pagos y organización del club"
            className="w-full"
          />
        </div>

        <TestimonialSlider />

      </div>
    </div>
  );
}

export default Testimonials;