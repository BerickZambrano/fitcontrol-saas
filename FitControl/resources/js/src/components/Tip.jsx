import React from "react";

function Tip({ tip, active, position }) {
  return (
    <div
      style={{ backgroundColor: active ? "#121c4c" : "#12092a" }}
      className={`w-[450px] h-[120px] text-white font-bold tracking-wider pl-7 pr-9 py-5 rounded-[32px] ${position % 2 === 0 ? "rounded-br-[0]" : "rounded-bl-[0]"
        } max-[1024px]:p-5 max-[1024px]:w-[45%] max-[1024px]:h-auto max-[768px]:w-[100%] max-[768px]:p-6`}
    >
      {tip}
    </div>
  );
}

export default Tip;