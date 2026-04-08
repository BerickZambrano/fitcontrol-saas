import React, { useRef, useState } from "react";
import downArrow from "../assets/triangulo.svg";

function Question({ question, answer }) {
  const answerRef = useRef(null);
  const [active, setActive] = useState(false);

  const handleQuestionClick = () => {
    setActive((prev) => {
      answerRef.current.style.paddingTop = !prev ? "12px" : "0";

      answerRef.current.style.maxHeight = !prev
        ? answerRef.current.scrollHeight + 12 + "px"
        : null;

      return !prev;
    });
  };

  return (
    <div className="cursor-pointer">
      <div
        onClick={handleQuestionClick}
        className="pb-1 flex items-center justify-between border-b-2 border-[#121c4c] hover:text-[#485179] transition-all duration-200"
      >
        <p className="font-bold text-xl tracking-wide text-[#12092a] max-[768px]:text-lg">
          {question}
        </p>

        <img
          src={downArrow}
          alt="arrow icon"
          className="w-[36px] h-[18px] transition-all duration-300"
          style={{ transform: active ? "rotate(180deg)" : "rotate(0deg)" }}
        />
      </div>

      <div
        ref={answerRef}
        className="overflow-hidden max-h-0 cursor-default transition-all duration-300"
      >
        <p className="text-[#12092a] font-medium tracking-wide">
          {answer}
        </p>
      </div>
    </div>
  );
}

export default Question;