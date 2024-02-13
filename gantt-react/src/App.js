import "gantt-task-react/dist/index.css";
// eslint-disable-next-line 
import { Gantt, Task, ViewMode } from "gantt-task-react";
import React, { useState, useEffect } from "react";
import { getStartEndDateForProject, initTasks } from "./ganttHelper.jsx";
import { ViewSwitcher } from "./components/views";

function App() {
  const [projectid, setProjectId] = useState(null);
  const [projectname, setProjectName] = useState(null);
  const [projecttask, setProjectTask] = useState(null);
  const [view, setView] = useState(ViewMode.Day);
  const [tasks, setTasks] = useState(initTasks(projectid));
  const [isChecked, setIsChecked] = useState(true);
  let columnWidth = 65;

  useEffect(() => {
    window.addEventListener('message', (event) => {
      // Identify correctness of message from iframe
      if (event.origin === "http://localhost") {
        if (event.data.key === 'projectid') {
          setProjectId(event.data.value);
        } else if (event.data.key === 'projectname') {
            setProjectName(event.data.value);
        } else if (event.data.key === 'task') {
          setProjectTask(event.data.value);
        }
      }
    });
  }, []);

  useEffect(() => {
    if (projectid) {
      // Run initTasks only when projectid has a value
      const initializedTasks = initTasks(projectid, projecttask);
      setTasks(initializedTasks);

      document.getElementById('projectname').innerText = projectname;
    }
  }, [projectid]);

  const handleTaskChange = (task) => {
    if (!projectid) return; // Prevent execution if projectid is null
    console.log("On date change Id:" + task.id);
    let newTasks = tasks.map((t) => (t.id === task.id ? task : t));
    // Rest of the function code
    setTasks(newTasks);
  };

  const handleTaskDelete = (task) => {
    if (!projectid) return; // Prevent execution if projectid is null
    const conf = window.confirm("Are you sure about " + task.name + " ?");
    if (conf) {
      const filteredTasks = tasks.filter((t) => t.id !== task.id);
      setTasks(filteredTasks);
    }
  };

  const handleProgressChange = async (task) => {
    if (!projectid) return; // Prevent execution if projectid is null
    console.log("On progress change Id:" + task.id);
  };

  const handleDblClick = (task) => {
    if (!projectid) return; // Prevent execution if projectid is null
    var elementToClick = "card" + task.id;
    window.parent.postMessage({
      key: "clickElement", value: elementToClick}, "http://localhost")
  };

  const handleSelect = (task, isSelected) => {
    if (!projectid) return; // Prevent execution if projectid is null
    console.log(task.name + " has " + (isSelected ? "selected" : "unselected"));
  };

  const handleExpanderClick = (task) => {
    if (!projectid) return; // Prevent execution if projectid is null
    console.log("On expander click Id:" + task.id);
    // Rest of the function code
  };

  if (!projectid) {
    return <div>Loading...</div>; // Render loading indicator until projectid is set
  }

  if (tasks.length === 0) {
    return <div>No tasks found for project {projectid}</div>; // Render message if no tasks are available
  }

  // Calculate columnWidth based on view mode
  if (view === ViewMode.Month) {
    columnWidth = 300;
  } else if (view === ViewMode.Week) {
    columnWidth = 250;
  }

  return (
      <div>
        <ViewSwitcher
            onViewModeChange={(viewMode) => setView(viewMode)}
            onViewListChange={setIsChecked}
            isChecked={isChecked}
        />
        <h3 id="projectname" style={{marginLeft: "5px"}}>PROJECT NAME</h3>
        <Gantt
            tasks={tasks}
            viewMode={view}
            onDateChange={handleTaskChange}
            onDelete={handleTaskDelete}
            onProgressChange={handleProgressChange}
            onDoubleClick={handleDblClick}
            onSelect={handleSelect}
            onExpanderClick={handleExpanderClick}
            listCellWidth={isChecked ? "155px" : ""}
            columnWidth={columnWidth}
        />
      </div>
  );
}

export default App;