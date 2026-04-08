import React from "react";

function OperatingStepsCard({ src, title, description }) {
  return (
    <div className="w-[350px] flex flex-col items-center justify-center gap-3 text-center max-[450px]:w-full">
      <img
        src={src}
        alt={title}
        className="w-[120px] h-[120px] aspect-square"
      />
      <h4 className="text-black font-bold text-2xl">{title}</h4>
      <p>{description}</p>
    </div>
  );
}

export default OperatingStepsCard;
