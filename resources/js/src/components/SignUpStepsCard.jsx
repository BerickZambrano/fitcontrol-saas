import React from "react";

function SignUpStepsCard({ index, title, description, src }) {
  return (
    <div className="w-[350px] flex flex-col justify-center items-stretch gap-3 text-center max-[420px]:w-full">

      <div
        className="w-[60px] h-[60px] mx-auto text-white rounded-full flex justify-center items-center text-2xl tracking-wide"
        style={{ background: "linear-gradient(#121c4c 0%, #485179 100%)" }}
      >
        0{index}
      </div>

      <h4 className="mx-auto text-[#121c4c] text-2xl font-bold tracking-wide max-w-[300px]">
        {title}
      </h4>

      <img src={src} alt={title} className="h-[164px] object-contain" />

      <p className="text-sm text-[#12092a]">
        {description}
      </p>

    </div>
  );
}

export default SignUpStepsCard;