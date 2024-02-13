export function initTasks(projectid, tasktoreplace) {
    const currentDate = new Date()
    if (tasktoreplace !== undefined) {
      return tasktoreplace
    } else {
      var tasks = [
        {
          start: new Date(currentDate.getFullYear(), currentDate.getMonth(), 3),
          end: new Date(currentDate.getFullYear(), currentDate.getMonth(), 15),
          name: projectid,
          id: "Project",
          progress: 45,
          type: "project",
          hideChildren: false,
          displayOrder: 1,
        },
        {
          start: new Date(currentDate.getFullYear(), currentDate.getMonth(), 1),
          end: new Date(currentDate.getFullYear(), currentDate.getMonth(), 2, 12, 28),
          name: "Idea",
          id: "Task 0",
          progress: 45,
          type: "task",
          project: "ProjectSample",
          displayOrder: 2,
        },
        {
          start: new Date(currentDate.getFullYear(), currentDate.getMonth(), 2),
          end: new Date(currentDate.getFullYear(), currentDate.getMonth(), 4, 0, 0),
          name: "Research",
          id: "Task 1",
          progress: 25,
          type: "task",
          project: "ProjectSample",
          displayOrder: 3,
        },
        {
          start: new Date(currentDate.getFullYear(), currentDate.getMonth(), 4),
          end: new Date(currentDate.getFullYear(), currentDate.getMonth(), 8, 0, 0),
          name: "Discussion with team",
          id: "Task 2",
          progress: 10,
          type: "task",
          project: "ProjectSample",
          displayOrder: 4,
        },
        {
          start: new Date(currentDate.getFullYear(), currentDate.getMonth(), 8),
          end: new Date(currentDate.getFullYear(), currentDate.getMonth(), 9, 0, 0),
          name: "Developing",
          id: "Task 3",
          progress: 2,
          type: "task",
          project: "ProjectSample",
          displayOrder: 5,
        },
        {
          start: new Date(currentDate.getFullYear(), currentDate.getMonth(), 8),
          end: new Date(currentDate.getFullYear(), currentDate.getMonth(), 10),
          name: "Review",
          id: "Task 4",
          type: "task",
          progress: 70,
          project: "ProjectSample",
          displayOrder: 6,
        },
        {
          start: new Date(currentDate.getFullYear(), currentDate.getMonth(), 15),
          end: new Date(currentDate.getFullYear(), currentDate.getMonth(), 15),
          name: "Release",
          id: "Task 6",
          progress: currentDate.getMonth(),
          type: "milestone",
          project: "ProjectSample",
          displayOrder: 7,
        },
        {
          start: new Date(currentDate.getFullYear(), currentDate.getMonth(), 18),
          end: new Date(currentDate.getFullYear(), currentDate.getMonth(), 19),
          name: "Party Time",
          id: "Task 9",
          progress: 0,
          isDisabled: true,
          type: "task",
        },
      ]
      return tasks
    }
  }
  
  export function getStartEndDateForProject(tasks, projectId) {
    const projectTasks = tasks.filter((t) => t.project === projectId)
    let start = projectTasks[0].start
    let end = projectTasks[0].end

    for (let i = 0; i < projectTasks.length; i++) {
      const task = projectTasks[i]
      if (start.getTime() > task.start.getTime()) {
        start = task.start
      }
      if (end.getTime() < task.end.getTime()) {
        end = task.end
      }
    }
    return [start, end]
  }