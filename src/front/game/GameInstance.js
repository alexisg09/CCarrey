

const grid = [];


const makeGrid = () => {

    for (let i = 0; i < 11; i++) {
        for (let j = 0; j < 11; j++) {
        grid.push([i,j])
    }
    }

    console.info('grid : ', grid)
}
