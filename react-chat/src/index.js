import React from "react";
import { createRoot } from "react-dom/client";
import App from "./App";

const container = document.getElementById("react-chat");

if (container) {
  const groupId = parseInt(container.dataset.groupId, 10);
  console.log("Mounting React app for group:", groupId);
  createRoot(container).render(<App groupId={groupId} />);
} else {
  console.warn("React container not found.");
}
