import React from "react";

function ShortCheckPointer({ text }) {
  return (
    <div className="flex items-center gap-2">
      <span className="text-[#121c4c] text-xl">✔</span>
      <span className="font-bold text-[#12092a]">{text}</span>
    </div>
  );
}

export default ShortCheckPointer;