import React from "react";
import laravel from "../assets/laravel-svgrepo-com.svg";
import react from "../assets/react-svgrepo-com.svg";
import html from "../assets/html-5-svgrepo-com.svg";
import tailwind from "../assets/tailwind-svgrepo-com.svg";

function Customers() {
  return (
    <div className="flex justify-center bg-[#12092a]">
      <div className="min-h-[350px] max-w-[1440px] w-full px-6 flex items-center justify-center flex-col gap-[50px] max-[1024px]:h-auto max-[1024px]:py-10 max-[768px]:gap-8">

        <h2 className="text-white text-[36px] font-bold text-center max-[768px]:text-2xl tracking-wide">
          Impulsamos el crecimiento de tu club{" "}
          <span className="text-[#485179]">!</span>
        </h2>

        <div className="flex items-center justify-center gap-3 flex-wrap">

          <img
            src={laravel}
            alt="Laravel Logo"
            className="w-[250px] h-[100px] max-[768px]:h-[75px]"
          />

          <div className="min-w-[1px] h-[60px] bg-[#485179] max-[798px]:hidden"></div>

          <img
            src={react}
            alt="React Logo"
            className="w-[250px] h-[100px] max-[768px]:h-[75px]"
          />

          <div className="min-w-[1px] h-[60px] bg-[#485179] max-[798px]:hidden"></div>

          <img
            src={html}
            alt="HTML Logo"
            className="w-[200px] h-[100px] max-[768px]:h-[75px]"
          />

          <div className="min-w-[1px] h-[60px] bg-[#485179] max-[1023px]:invisible max-[798px]:hidden"></div>

          <img
            src={tailwind}
            alt="Tailwind Logo"
            className="w-[200px] h-[100px] max-[768px]:h-[75px]"
          />

        </div>
      </div>
    </div>
  );
}

export default Customers;